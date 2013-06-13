<?php 

/**
 * ApiKeyController class
 *
 * Handler for creating new API Keys
 * 
 * PHP version 5
 *
 * @author		Bill Hunt <bill at krues8dr dot com>
 * @copyright	2013 Bill Hunt
 * @license		http://www.gnu.org/licenses/gpl.html GPL 3
 * @version		0.8
 * @link		http://www.statedecoded.com/
 * @since		0.8
 */

class ApiKeyController extends BaseController
{
	protected $api;
	function __construct() {
		parent::__construct();
		
		
		/*
		 * Create an instance of the API class.
		 */
		$this->api = new API();
	}

	/**
	 * Activate an API Key
	 */
	function activateKey($args)
	{
		
		$this->setContent('browser_title', 'Activate API Key');
		$this->setContent('page_title', 'Activate API Key');
		
		/*
		 * If this isn't a five-character string, bail -- something's not right.
		 */
		if (strlen($args['secret']) != 5) {
			$body .= '<h2>Error</h2>
				<p>Invalid API key.</p>';
		} else {
			$this->api->secret = $args['secret'];
			$this->api->activate_key();
			
			if($this->api->key) {
				$body .= '<h2>API Key Activated</h2>
				
					<p>Your API key has been activated. You may now make requests from the API. Your
					key is:</p>
				
					<p><code>'.$this->api->key.'</code></p>';
			} else {
				$body .= '<h2>Error</h2>
					<p>Invalid API key.</p>';
			}
		}
		
		$this->setContent('body', $body);
		
		return $this->renderContent();
	}
	
	function requestKey()
	{ 
		/*
		 * Define some page elements.
		 */
		$this->setContent('browser_title', 'Register for an API Key');
		$this->setContent('page_title', 'Register for an API Key');

		/*
		 * Provide some custom CSS for this form.
		 */
		$this->setContent('inline_css', 
			'<style>
				#required-note {
					font-size: .85em;
					margin-top: 2em;
				}
				.required {
					color: #f00;
				}
				#api-registration label {
					display: block;
					margin-top: 1em;
				}
				#api-registration input[type=text] {
					width: 35em;
				}
				#api-registration input[type=submit] {
					display: block;
					clear: left;
					margin-top: 1em;
				}
			</style>');


		/*
		 * Define the sidebar.
		 */
		$sidebar = '<h1>Nota Bene</h1>
			<section>
				<p>'.SITE_TITLE.' is not your database. Cache accordingly.</p>
		
				<p>Consider whether <a href="/downloads/">a bulk download</a> might be more appropriate
				for your purposes.</p>
			</section>';

		/*
		 * Put the shorthand $sidebar variable into its proper place.
		 */
		$this->setContent('sidebar', $sidebar);
		unset($sidebar);

		/*
		 * If the form on this page is being submitted, process the submitted data.
		 */
		if (isset($_POST['form_data'])) {

			/*
			 * Pass the submitted form data to the API class, as an object rather than as an array.
			 */
			$form = (object) $_POST['form_data'];
	
			/*
			 * If this form hasn't been completed properly, display the errors and re-display the form.
			 */
			$form_errors = $this->validate_form($form);
			
			if ($form_errors !== FALSE) {
				$body .= '<p class="error">Error: '.$form_errors.'</p>';
				$body .= $this->display_form();
			} else {	
				/*
				 * But if the form has been filled out correctly, then proceed with the registration process.
				 */
				try {
					$this->api->register_key();
				} catch (Exception $e) {
					$body = '<p class="error">Error: ' . $e->getMessage() . '</p>';
				}
		
				$body .= '<p>You have been sent an e-mail to verify your e-mail address. Please click the
							link in that e-mail to activate your API key.</p>';
			}
		} else {
			/* If this page is being loaded normally (that is, without submitting data), then display the registration
			 * form.
			 */
			$body = $this->display_form();
		}

		/*
		 * Put the shorthand $body variable into its proper place.
		 */
		$this->setContent('body', $body);
		unset($body);
		
		return $this->renderContent();
	}
	
	
	
	/**
	 * Display a registration form.
	 */
	function display_form()
	{
	
		$form = '
			<form method="post" action="/api-key/" id="api-registration">
				
				<label for="name">Your Name</label>
				<input type="name" id="name" name="form_data[name]" placeholder="John Doe" value="'.$this->form->name.'" />
				
				<label for="email">E-Mail Address <span class="required">*</span></label>
				<input type="email" id="email" name="form_data[email]" placeholder="john_doe@example.com" required value="'.$this->form->email.'" />
				
				<label for="url">Website URL</label>
				<input type="url" id="url" name="form_data[url]" placeholder="http://www.example.com/" value="'.$this->form->url.'" />
				
				<input type="submit" value="Submit" />
				
			</form>
			
			<p id="required-note"><span class="required">*</span> Required field</p>';
		
		return $form;
	}
	
	
	/**
	 * Validate a submitted form.
	 */
	function validate_form($form)
	{
		$form_errors = FALSE;
		
		if (!isset($form)) {
			$form_errors = 'Please fill out all required fields';
		} elseif (empty($form->email)) {
			$form_errors = 'Please provide your e-mail address.';
		} elseif (filter_var($form->email, FILTER_VALIDATE_EMAIL) === FALSE) {
			$form_errors = 'Please enter a valid e-mail address.';
		} elseif (filter_var($form->url, FILTER_VALIDATE_URL) === FALSE) {
			$form_errors = 'Please enter a valid URL.';
		}
		
		return $form_errors;
	}
	
}
