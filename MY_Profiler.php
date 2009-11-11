<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Profiler extends CI_Profiler {

	/**
	 * Adds session data to the profiler
	 * Adds a table row for each item of session data with the key and value
	 * Shows both CI session data and custom session data
	 */

	function _compile_benchmarks()
 	{
  		$profile = array();
 		foreach ($this->CI->benchmark->marker as $key => $val)
 		{
 			// We match the "end" marker so that the list ends
 			// up in the order that it was defined
 			if (preg_match("/(.+?)_end/i", $key, $match))
 			{ 			
 				if (isset($this->CI->benchmark->marker[$match[1].'_end']) AND isset($this->CI->benchmark->marker[$match[1].'_start']))
 				{
 					$profile[$match[1]] = $this->CI->benchmark->elapsed_time($match[1].'_start', $key);
 				}
 			}
 		}

		// Build a table containing the profile data.
		// Note: At some point we should turn this into a template that can
		// be modified.  We also might want to make this data available to be logged
				
		$output = "<table>
			<colgroup>
				<col style='width:50%'/>
				<col/>
			</colgroup>
			<thead>
			<tr>
				<th>Resource</th>
				<th>Value</th>
			</tr>
			</thead>
			<tbody>";
				
		$tr_class = "djDebugOdd";
		
		foreach ($profile as $key => $val)
		{
			$key = ucwords(str_replace(array('_', '-'), ' ', $key));
			$output .= "<tr class='$tr_class'><td>".$key."</td><td>".$val."</td></tr>";
			if ($tr_class == "djDebugOdd") { $tr_class = "djDebugEven"; } elseif  ($tr_class == "djDebugEven") { $tr_class = "djDebugOdd"; };
		}
		
		$output .= "</tbody></table>";
 		
 		return $output;
 	}

	function _compile_uri_string()
	{	
		
		if ($this->CI->uri->uri_string == '')
		{
			$output = "<p>No URI data</p>";
		}
		else
		{
			$output = "<p>".$this->CI->uri->uri_string."</p>";				
		}

		return $output;	
	}
	
	function _compile_cookie()
	{	
		
		$output = "<p>No available yet</p>";

		return $output;	
	}

    function _compile_session()
	{

        if (!is_object($this->CI->session))
		{
            $output .= "<p>No SESSION data</p>";
        } 
		else
		{
		    $output = "<table>
				<colgroup>
					<col style='width:20%'/>
					<col/>
				</colgroup>
				<thead>
				<tr>
					<th>Variable</th>
					<th>Value</th>
				</tr>
				</thead>
				<tbody>";

			$tr_class = "djDebugOdd";
			
            $sess = get_object_vars($this->CI->session);

            foreach ($sess['userdata'] as $key => $val)
			{
                if ( ! is_numeric($key))
				{
                    $key = "'".$key."'";
                }

                $output .= "<tr class='$tr_class'><td>&#36;_SESSION[".$key."]</td><td>";

                if (is_array($val))
				{
                    $output .= "<pre>" . htmlspecialchars(stripslashes(print_r($val, true))) . "</pre>";
                } 
				else
				{
                    $output .= htmlspecialchars(stripslashes($val));
                }

                $output .= "</td></tr>";
				if ($tr_class == "djDebugOdd") { $tr_class = "djDebugEven"; } elseif  ($tr_class == "djDebugEven") { $tr_class = "djDebugOdd"; };
				
            }

			$output .= "</tbody></table>";
        }

        return $output;    
    }

	function _compile_get()
	{	
				
		if (count($_GET) == 0)
		{
			$output = "<p>No GET data</p>";
		}
		else
		{
			$output = "<table>
				<colgroup>
					<col style='width:20%'/>
					<col/>
				</colgroup>
				<thead>
				<tr>
					<th>Variable</th>
					<th>Value</th>
				</tr>
				</thead>
				<tbody>";
				
			$tr_class = "djDebugOdd";
		
			foreach ($_GET as $key => $val)
			{
				if ( ! is_numeric($key))
				{
					$key = "'".$key."'";
				}
			
				$output .= "<tr class='$tr_class'><td>&#36;_GET[".$key."]</td><td>";
				if (is_array($val))
				{
					$output .= "<pre>" . htmlspecialchars(stripslashes(print_r($val, true))) . "</pre>";
				}
				else
				{
					$output .= htmlspecialchars(stripslashes($val));
				}
				$output .= "</td></tr>";
				if ($tr_class == "djDebugOdd") { $tr_class = "djDebugEven"; } elseif  ($tr_class == "djDebugEven") { $tr_class = "djDebugOdd"; };
			}
			
			$output .= "</tbody></table>";
		}

		return $output;	
	}
	
	function _compile_post()
	{	
				
		if (count($_POST) == 0)
		{
			$output = "<p>No POST data</p>";
		}
		else
		{
			$output = "<table>
				<colgroup>
					<col style='width:20%'/>
					<col/>
				</colgroup>
				<thead>
				<tr>
					<th>Variable</th>
					<th>Value</th>
				</tr>
				</thead>
				<tbody>";
				
			$tr_class = "djDebugOdd";
		
			foreach ($_POST as $key => $val)
			{
				if ( ! is_numeric($key))
				{
					$key = "'".$key."'";
				}
			
				$output .= "<tr class='$tr_class'><td>&#36;_POST[".$key."]</td><td>";
				if (is_array($val))
				{
					$output .= "<pre>" . htmlspecialchars(stripslashes(print_r($val, true))) . "</pre>";
				}
				else
				{
					$output .= htmlspecialchars(stripslashes($val));
				}
				$output .= "</td></tr>";
				if ($tr_class == "djDebugOdd") { $tr_class = "djDebugEven"; } elseif  ($tr_class == "djDebugEven") { $tr_class = "djDebugOdd"; };
			}
			
			$output .= "</tbody></table>";
		}

		return $output;	
	}
	
	function _compile_controller_info()
	{	
		$output = "<p>".$this->CI->router->fetch_class()."/".$this->CI->router->fetch_method()."</p>";				

		return $output;	
	}
	
	function _compile_memory_usage()
	{
		
		if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '')
		{
			$output = number_format($usage).' bytes';
		}
		else
		{
			$output = "N/A";				
		}

		return $output;
	}
	
	function _compile_queries()
	{
		$dbs = array();

		// Let's determine which databases are currently connected to
		foreach (get_object_vars($this->CI) as $CI_object)
		{
			if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB') )
			{
				$dbs[] = $CI_object;
			}
		}
					
		if (count($dbs) == 0)
		{
			$output = "<h2>".$this->CI->lang->line('profiler_queries')."<h2>";	
			
			return $output;
		}
		
		// Load the text helper so we can highlight the SQL
		$this->CI->load->helper('text');

		// Key words we want bolded
		$highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');
			
		foreach ($dbs as $db)
		{
			$output = "<h4>Database:&nbsp; ".$db->database."&nbsp;QUERIES: ".count($this->CI->db->queries)."</h4>";		
		
			if (count($db->queries) == 0)
			{
				$output .= "<p>No queries were run</p>";
			}
			else
			{	
				$output = "<table>
					<colgroup>
						<col style='width:20%'/>
						<col/>
					</colgroup>
					<thead>
					<tr>
						<th>Time</th>
						<th>Query</th>
					</tr>
					</thead>
					<tbody>";

				$tr_class = "djDebugOdd";
							
				foreach ($db->queries as $key => $val)
				{					
					$time = number_format($db->query_times[$key], 4);

					$val = highlight_code($val, ENT_QUOTES);
	
					foreach ($highlight as $bold)
					{
						$val = str_replace($bold, '<strong>'.$bold.'</strong>', $val);	
					}
					
					$output .= "<tr class='$tr_class'><td>".$time."</td><td>".$val."</td></tr>";
					if ($tr_class == "djDebugOdd") { $tr_class = "djDebugEven"; } elseif  ($tr_class == "djDebugEven") { $tr_class = "djDebugOdd"; };
				}
			}
			
			$output .= "</tbody></table>";
			
		}
		
		return $output;
	}

    function run()
	{
		
	/*
	* Adds session data to the profiler
	* Adds a table row for each item of session data with the key and value
	* Shows both CI session data and custom session data
	*/
		
		$media_path = "/Codeigniter/media/application/debug_toolbar";
		$ci_version = CI_VERSION;
		$cidt_version = "0.1.u";

	/*
	* Adds session data to the profiler
	* Adds a table row for each item of session data with the key and value
	* Shows both CI session data and custom session data
	*/

		$output = "
		<!-- Start CodeIgniter Debug Toolbar -->
		<script type='text/javascript' charset='utf-8'>
			// When jQuery is sourced, it's going to overwrite whatever might be in the
			// '$' variable, so store a reference of it in a temporary variable...
			var _$ = window.$;
			if (typeof jQuery == 'undefined') { 
				var jquery_url = '$media_path/jquery.js'; 
				document.write(unescape('%3Cscript src=\"' + jquery_url + '\" type=\"text/javascript\"%3E%3C/script%3E'));
			}
		</script>
		<script type='text/javascript' src='$media_path/toolbar.min.js'></script>
		<script type='text/javascript' charset='utf-8'>
			// Now that jQuery is done loading, put the '$' variable back to what it was...
			var $ = _$;
		</script>
		<style type='text/css'>
			@import url($media_path/toolbar.min.css);
		</style>
		<div id='djDebug'>
		";
		
		$output .= "
			<div style='display:none;' id='djDebugToolbar'>
				<ul id='djDebugPanelList'>
					<li><a id='djHideToolBarButton' href='#' title='Hide Toolbar'>Hide &raquo;</a></li>
						<li>		
							<a href='#' title='Versions' class='djDebugVersionPanel'>					
							Versions
							<br><small>CodeIgniter $ci_version</small>
							</a>
						</li>
						<li>
							<a href='#' title='Bench Mark' class='djDebugBenchmarkPanel'>
							Bench Mark
							<br><small>Execution: elapsed_time s</small>
							<br><small>Memory: ".$this->_compile_memory_usage()."</small>
							</a>			
						</li>
						<li>
							<a href='#' title='HTTP Headers' class='djDebugHeaderPanel'>
							HTTP Headers
							</a>
						</li>
						<li>
							<a href='#' title='Request Vars' class='djDebugRequestVarsPanel'>
							Request Vars
							</a>
						</li>
						<li>
							<a href='#' title='SQL Queries' class='djDebugSQLPanel'>
							SQL
							<br><small>0 queries in 0.00ms</small>
							</a>
						</li>
				</ul>
			</div>
		";
			
		$output .= "
			<div style='display:none;' id='djDebugToolbarHandle'>
				<a title='Show Toolbar' id='djShowToolBarButton' href='#'>&laquo;</a>
			</div>
		";

		$output .= "
			<div id='djDebugVersionPanel' class='panelContent'>
				<div class='djDebugPanelTitle'>
					<a href='' class='close'>Close</a>
					<h3>Versions</h3>
				</div>
				<div class='djDebugPanelContent'>
					<div class='scroll'>
					<table>
						<thead>
						<tr>
							<th>Package</th>
							<th>Version</th>
						</tr>
						</thead>
						<tbody>
						<tr class='djDebugOdd'>
							<td>Debug Toolbar</td>
							<td>$cidt_version</td>
						</tr>
						<tr class='djDebugEven'>
							<td>CodeIgniter</td>
							<td>$ci_version</td>
						</tr>
						</tbody>
					</table>
					</div>
				</div>
			</div>
		";

		$output .= "
			<div id='djDebugBenchmarkPanel' class='panelContent'>
				<div class='djDebugPanelTitle'>
					<a href='' class='close'>Close</a>
					<h3>Bench Mark</h3>
				</div>
				<div class='djDebugPanelContent'>
					<div class='scroll'>
					".$this->_compile_benchmarks()."
					</div>
				</div>
			</div>
		";

		$output .= "
			<div id='djDebugHeaderPanel' class='panelContent'>
				<div class='djDebugPanelTitle'>
					<a href='' class='close'>Close</a>
					<h3>HTTP Headers</h3>
				</div>
				<div class='djDebugPanelContent'>
					<div class='scroll'>
					<table>
						<thead>
						<tr>
							<th>Key</th>
							<th>Value</th>
						</tr>
						</thead>
						<tbody>
						<tr class='djDebugOdd'>
							<td>HTTP_ACCEPT</td>
							<td>text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8</td>
						</tr>
						<tr class='djDebugEven'>
							<td>HTTP_ACCEPT_CHARSET</td>
							<td>UTF-8,*</td>
						</tr>
						<tr class='djDebugOdd'>
							<td>HTTP_USER_AGENT</td>
							<td>Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; ko; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5 GTB6</td>
						</tr>
						<tr class='djDebugEven'>
							<td>HTTP_CONNECTION</td>
							<td>keep-alive</td>
						</tr>
						<tr class='djDebugOdd'>
							<td>SERVER_NAME</td>
							<td>django.unolee.com</td>
						</tr>
						<tr class='djDebugEven'>
							<td>REMOTE_ADDR</td>
							<td>121.166.169.1</td>
						</tr>
						<tr class='djDebugOdd'>
							<td>SERVER_SOFTWARE</td>
							<td>Apache/2.2.11 (Unix) mod_ssl/2.2.11 OpenSSL/0.9.7a Phusion_Passenger/2.2.4 mod_auth_passthrough/2.1 mod_bwlimited/1.4 FrontPage/5.0.2.2635</td>
						</tr>
						<tr class='djDebugEven'>
							<td>HTTP_ACCEPT_LANGUAGE</td>
							<td>ko-kr,ko;q=0.8,en-us;q=0.5,en;q=0.3</td>
						</tr>
						<tr class='djDebugOdd'>
							<td>SCRIPT_NAME</td>
							<td></td>
						</tr>
						<tr class='djDebugEven'>
							<td>REQUEST_METHOD</td>
							<td>GET</td>
						</tr>
						<tr class='djDebugOdd'>
							<td>HTTP_HOST</td>
							<td>django.unolee.com</td>
						</tr>
						<tr class='djDebugEven'>
							<td>HTTP_KEEP_ALIVE</td>
							<td>300</td>
						</tr>
						<tr class='djDebugOdd'>
							<td>SERVER_PORT</td>
							<td>80</td>
						</tr>
						<tr class='djDebugEven'>
							<td>SERVER_PROTOCOL</td>
							<td>HTTP/1.1</td>
						</tr>
						<tr class='djDebugOdd'>
							<td>QUERY_STRING</td>
							<td></td>
						</tr>
						<tr class='djDebugEven'>
							<td>HTTP_CACHE_CONTROL</td>
							<td>max-age=0</td>
						</tr>
						<tr class='djDebugOdd'>
							<td>HTTP_ACCEPT_ENCODING</td>
							<td>gzip,deflate</td>
						</tr>
						</tbody>
					</table>
					</div>
				</div>
			</div>
		";

		$output .= "
			<div id='djDebugRequestVarsPanel' class='panelContent'>
				<div class='djDebugPanelTitle'>
					<a href='' class='close'>Close</a>
					<h3>Request Vars</h3>
				</div>
				<div class='djDebugPanelContent'>
					<div class='scroll'>
						
					<h4>URI Strings</h4>
					".$this->_compile_uri_string()."
					
					<h4>CLASS / METHOD</h4>
					".$this->_compile_controller_info()."
					
					<h4>COOKIES Variables</h4>
					".$this->_compile_cookie()."

					<h4>SESSION Variables</h4>
					".$this->_compile_session()."

					<h4>GET Variables</h4>
					".$this->_compile_get()."

					<h4>POST Variables</h4>
					".$this->_compile_post()."

					</div>
				</div>
			</div>
		";

		$output .= "
			<div id='djDebugSQLPanel' class='panelContent'>
				<div class='djDebugPanelTitle'>
					<a href='' class='close'>Close</a>
					<h3>SQL Queries</h3>
				</div>
				<div class='djDebugPanelContent'>
					<div class='scroll'>
					".$this->_compile_queries()."
					</div>
				</div>
			</div>
		";

		$output .= "
			<div id='djDebugWindow' class='panelContent'></div>
			
		</div>
		<!-- End CodeIgniter Debug Toolbar -->
		";
	
        return $output;
		
    }
} 