<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logo extends MY_Controller 
{

	public function index()
	{
		redirect('logo/customise');
	}

	/**
	 * Show the customisation form
	 * load locals and available subtexts for customisation
	 */
	public function customise()
	{
		$qSubtext = $this->db->query('SELECT language, subtext FROM logo_subtext ORDER BY language ASC');
		
		$stat = $this->db->query('SELECT AVG(execTime) as `avTime`, SUM(execTime) as `totTime`,  MAX(`execTime`) as `maxTime`, COUNT(*) as `requests`, timestamp as `since` FROM generator WHERE status = 100 ORDER BY timestamp ASC')->row();
		$stats['avExecTime'] = ($stat->avTime !== null) ? round($stat->avTime, 2) : 0 ;
		$stats['totExecTime'] = ($stat->totTime !== null) ? round($stat->totTime, 2) : 0 ;
		$stats['maxExecTime'] = ($stat->maxTime !== null) ? round($stat->maxTime, 2) : 0 ;
		$stats['totRequests'] = ($stat->requests !== null) ? $stat->requests : 0 ;
		$stats['maxExecTimeRounded'] = ($stat->requests !== null) ? ceil($stat->maxTime/10)*10 : 0 ;
		$stats['since'] = ($stat->since !== null) ? $stat->since : 0 ;

		$this->db->db_select('ab');
		$qLocals = $this->db->query('SELECT BodyCode, BodyName FROM bodies WHERE `BodyCategory` = "Locals" ORDER BY BodyName ASC');
		
		// go back to default table
		$this->db->db_select('logo-generator');
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		parent::assign('subtexts', $qSubtext->result());
		parent::assign('stats', $stats);
		parent::assign('csrf', $csrf);
		parent::assign('form',$csrf);
		parent::assign('locals', $qLocals->result());	
		parent::view('logo-generation/logo');
	}
	
	/**
	 * Proxyy for generating request 
	 * uses curl to call "internal" script to have some asynchronous behaviour!
	 * returns json for ajax processing
	 */
	public function generate()
	{
		$this->load->library('form_validation');
		// a new generation call has been initiated, thus reset old one.
		$request['local'] = $this->input->post('local', TRUE);
		$request['subtext'] = $this->input->post('subtext', TRUE);
		$request['format'] = $this->input->post('format', TRUE);
		$request['size'] = $this->input->post('size', TRUE);
		$request['colour'] = $this->input->post('colour', TRUE);
		$request['extra'] = $this->input->post('extra', TRUE);
		$request['token'] = $this->input->post('token', TRUE );
		$return['post'] = $this->input->post(NULL, TRUE);
		
		$this->load->model('logomodel');
		$progress = $this->logomodel->getProgress($request['token']);

		// let's go asynchronous!
		// we want to be able to have some status updates while waiting
		// therefore curl the trigger to 
		if($progress['status'] == 0)
		{					
			$url = "http://zeus.aegee.org/logo-generator/logo/internalGeneration";
			$data = http_build_query($request);

			$ch = curl_init();
 
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
            
			$output = curl_exec($ch);
			curl_close($ch);

			$return['code'] = 201;
			$return['message'] = 'Starting...';
			$return['data'] = $data;
			$return['progress'] = $progress;
			
			$csrf = array(
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			);
			
			parent::assign($return);
			parent::assign('token', $request['token']);
			parent::assign('csrf', $csrf);
			parent::view('logo-generation/generate');			
		}
	
		// there are progress variables, so continue
		else
		{
			$this->status();
		}
		
	}
	
	/**
	 * Should only be executed once!
	 */
	public function internalGeneration()
	{
		$this->load->library('form_validation');	
		$token = $this->input->post( 'token', TRUE);
		parent::assign('token', $token);
		
		$this->load->model('logomodel');
		$resultInit = $this->logomodel->init($token);
		
		$return['code'] = 201;
		$return['message'] = 'Generating for '. $token;

		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		
		parent::assign($return);				
		parent::assign('csrf', $csrf);
		parent::view('logo-generation/generate');
	}
	
	/**
	 * Retrieve status on progress
	 * returns json for ajax processing
	 */
	public function status($token = false)
	{
		$token = ($token != false) ? $token : $this->input->get('token', TRUE);

		$this->load->model('logomodel');
		$progress = $this->logomodel->getProgress($token);

		// redirect and provide download
		if($progress['status'] >= 99)
		{
			// download link can be returned
			$return['code'] = 201;
			$progress['message'] = 'Finished';
			
			$zipFiles = $this->logomodel->getZipFilenames($token);
				
			if($zipFiles != false)
			{
				$size = filesize($zipFiles['source']);
				$sizeMB = round($size / (1024 * 1024), 2);
				parent::assign('downloadSize',  $sizeMB);
				parent::assign('downloadLink',  site_url("logo/download/".$token));
			}
		}		
		else
		{
			$return['code'] = 201;
		}
		
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
			
		parent::assign($return);
		parent::assign('token', $token);
		parent::assign('csrf', $csrf);
		parent::assign('progress', $progress);
		parent::view('logo-generation/generate');
	}
	
	/**
	 * Provide download link
	 */
	public function download($token = false)
	{
		$token = ($token != false) ? $token : $this->input->get('token', TRUE);

		// no token presented, don't know what to serve as download.
		// let's redirect to download tool
		if($token == false)
		{
			redirect('/logo/customise');
		}
		
		$this->load->model('logomodel');
		
		$progress = $this->logomodel->getProgress($token);
		
		// the generation was not finished yet while the download was already requested
		// redirect to status page for this token
		if($progress['status'] < 90)
		{
			redirect('/logo/status/'.$token);
			return false;
		}
		
		$zipFiles = $this->logomodel->getZipFilenames($token);
	
		if($zipFiles != false)
		{
			$this->load->helper('download');
			$data = file_get_contents($zipFiles['source']); // Read the file's contents
			
			force_download($zipFiles['target'], $data);
		}
		else
		{
			// return error
			$return['code'] = 404;
			$return['message'] = 'Could not find the download file. Please try again.';
			$return['data'] = array(
				'progress' => $progress,
				'zipFiles' => $zipFiles
			);	
			$csrf = array(
				'name' => $this->security->get_csrf_token_name(),
				'hash' => $this->security->get_csrf_hash()
			);
				
			parent::assign('token', $token);
			parent::assign('csrf', $csrf);
			parent::assign($return);
			parent::view('logo-generation/generate');
		}
	}

	/**
	 * Cancels the generation process
	 */

	public function cancel($token = false)
	{
		$token = ($token != false) ? $token : $this->input->get('token', TRUE);
		
		$this->load->model('logomodel');
		if($this->logomodel->cancel($token)){
			$return['code'] = 452;
			$return['message'] = 'Successfully cancelled the request.';
		} else{
			$return['code'] = 404;
			$return['message'] = 'Could not cancel the request.';
		}
		parent::assign('token', $token);
		parent::assign($return);
		parent::view('logo-generation/customise');
	}
}
