<?php
namespace Brosland\Components\Uploader;

use Brosland\Application\UI\Control,
	Brosland\Media\BadFileUploadedException,
	Nette\Http\FileUpload;

class Uploader extends Control
{
	/** @var string */
	private $tempDir;
	/** @var array */
	private $fileTypes;
	/** @var string */
	private $maxFileSize = '2gb';
	/** @var callback */
	public $onSuccess;
	
	
	/**
	 * @param string
	 * @param array
	 */
	public function __construct(array $fileTypes)
	{
		parent::__construct();
		
		$this->tempDir = WWW_DIR . '/plugins/plupload/temp';
		$this->fileTypes = $fileTypes;
	}
	
	/**
	 * @return string
	 */
	public function getToken()
	{
		return md5($this->lookupPath('Nette\Application\UI\Presenter'));
	}
	
	/**
	 * @param string
	 */
    public function setTempDir($tempDir)
    {
        if(!is_dir($tempDir))
		{
            if(!mkdir($tempDir))
			{
                throw new \Exception('Cannot create temp directory.');
            }
        }
		
        $this->tempDir = $tempDir;
    }

	/**
	 * @param string
	 */
	public function setMaxFileSize($maxFileSize)
	{
		$this->maxFileSize = $maxFileSize;
	}
	
	public function render()
	{		
		$this->template->setFile(__DIR__ . '/templates/uploader.latte');
		$this->template->token = $this->getToken();
		$this->template->maxFileSize = $this->maxFileSize;
		$this->template->extensions = implode(',', array_unique($this->fileTypes));
		$this->template->render();
	}
	
	public function handlePing()
	{
		$this->parent->invalidateControl();
	}
	
    public function handleUpload()
    {
        if(empty($this->tempDir))
		{
            throw new \Exception('Missing temp directory.');
        }
		
        // HTTP headers for no cache etc
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Settings
        //$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
        $targetDir = $this->tempDir . '/';

        //$cleanupTargetDir = false; // Remove old files
        //$maxFileAge = 60 * 60; // Temp file age in seconds

        // 5 minutes execution time
        @set_time_limit(5 * 60);

        // Uncomment this one to fake upload time
        // usleep(5000);

        // Get parameters
        $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
        $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
        $fileNameOriginal = $fileName;
        $fileName = sha1($this->token.$chunks.$fileNameOriginal);
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

        // Clean the fileName for security reasons
        $fileName = preg_replace('/[^\w\._]+/', '', $fileName);

        // Make sure the fileName is unique but only if chunking is disabled
        if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
                $ext = strrpos($fileName, '.');
                $fileName_a = substr($fileName, 0, $ext);
                $fileName_b = substr($fileName, $ext);

                $count = 1;
                while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
                        $count++;

                $fileName = $fileName_a . '_' . $count . $fileName_b;
        }
        
        // Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
                $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
                $contentType = $_SERVER["CONTENT_TYPE"];

        // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false) {
                if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                        // Open temp file
                        $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
                        if ($out) {
                                // Read binary input stream and append it to temp file
                                $in = fopen($_FILES['file']['tmp_name'], "rb");

                                if ($in) {
                                        while ($buff = fread($in, 4096))
                                                fwrite($out, $buff);
                                } else
                                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                                fclose($in);
                                fclose($out);
                                @unlink($_FILES['file']['tmp_name']);
                        } else
                                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
                } else
                        die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
        } else {
                // Open temp file
                $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
                if ($out) {
                        // Read binary input stream and append it to temp file
                        $in = fopen("php://input", "rb");

                        if ($in) {
                                while ($buff = fread($in, 4096))
                                        fwrite($out, $buff);
                        } else
                                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

                        fclose($in);
                        fclose($out);
                } else
                        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
        
        $file = null;
        $nonChunkedTransfer = ($chunk == 0 AND $chunks == 0);
        $lastChunk = ($chunk+1) == $chunks;
        if($lastChunk OR $nonChunkedTransfer) {
			// Hotovo
			$fileUpload = new FileUpload(array(
				'name' => $fileNameOriginal,
				'type' => "",
				'size' => filesize($filePath),
				'tmp_name' => $filePath,
				'error' => UPLOAD_ERR_OK
			));
			
			try
			{
				if(!(empty($this->fileTypes) || isset($this->fileTypes[$fileUpload->getContentType()])))
				{
					throw new BadFileUploadedException('Nepovolený formát súboru!');
				}
				
				$this->onSuccess($fileUpload);
			}
			catch(\Exception $e)
			{
				@unlink($filePath);
				
				$this->presenter->payload->error = TRUE;
				$this->presenter->payload->message = $e->getMessage();
				$this->presenter->sendPayload();
			}
        }
		
		// Return JSON-RPC response
		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }
}