<?php
if (!defined('APPLICATION')) exit();
// Define the plugin:
// the 'GuestNoLinks' is the name of the Plugin folder
$PluginInfo['GuestNoLinks'] = array(
	// just the name to display of the plugin below
	'Name' => 'Guest No Links',
	// a brief description
	'Description' => 'This plugin prevents guests from viewing links and files. One must register and log in to view them.',
	// each time you update plugin update version so you can upload it.
	'Version' => '1.0',
	'Author' => "aldorr", // the author,
	'AuthorEmail' => 'GuestNoLinksPlugin@aldorr.net',
	'AuthorURL' => 'http://aldorr.net'
	
);
     
class GuestNoLinksPlugin extends Gdn_Plugin {
	//<a href=/entry/signin?Target=discussion%2F2%2Fsetup-plugin> Sorry, this attachment is only viewable if you log in.</a>";
	public function DiscussionController_BeforeDiscussionDisplay_Handler($Sender) {
		// get current session info
		$Session = Gdn::Session();
		// this tests to see is user is logged in
		if (!($Session->IsValid())) {
			// if the user is NOT logged in - add this css file which resides
			// in the /plugins/GuestNoLinks design folder
			$Sender->AddCssFile('guestnolinks.css', 'plugins/GuestNoLinks');
		}
	}
	public function CategoriesController_BeforeDiscussionMeta_Handler(&$Sender) {
		// the call to discussioncontroller at the BeforeDiscussionMeta event
		// How did I find where to put this - I searched for FireEvent
		//in helper functions in
		// the /vanilla/applications/vanilla/views/discussion folder.
		$this->DiscussionController_BeforeCommentMeta_Handler($Sender);
	}
	public function DiscussionController_AfterDiscussionBody_Handler($Sender) {
		$Session = Gdn::Session();
		if (!($Session->IsValid())) {
			
		}
	}
	public function Base_Render_Before(&$Sender)
	{
		
		$Session = Gdn::Session();
			if (!($Session->IsValid())) {
			//put together javascript here
			$JavaScript = '
			<script type="text/javascript">
				
				function toArray(obj) {
				  var array = [];
				  // iterate backwards ensuring that length is an UInt32
				  for (var i = obj.length >>> 0; i--;) { 
					array[i] = obj[i];
				  }
				  return(array);
				}
				
				function convert()
				{				
	
					/* Replace each image in messages */
					$(".Message img").each(function() {
						$(this).replaceWith(\'<a href="/forum/entry/signin">Bitte einloggen, um alles anzusehen.</a>\');
						
						//set path to replacement image
						//imj = "/plugins/GuestNoLinks/design/content.png";
						//update image source
						//$(this).attr(\'src\',imj);
						//make image visible again
						$(this).css("display", "inline");
					});
					
					/* Now replace links. */
					$(\'.Message a\').each(function() {
						loginlink = "/forum/entry/signin";
						$(this).attr(\'href\',loginlink);
						$(this).css("display", "inline");
					});
					
					/* Now replace embedded documents. */
					$(".Message embed").each(function() {
						$(this).replaceWith(\'<a href="/forum/entry/signin">Bitte einloggen, um alles anzusehen.</a>\');
						
						//set path to replacement image
						//imj = "/plugins/GuestNoLinks/design/content.png";
						//update image source
						//$(this).attr(\'src\',imj);
						//make image visible again
						$(this).css("display", "inline");
					});
				}
				
				if(window.attachEvent) {
					window.attachEvent(\'onload\', convert);
				} else {
					if(window.onload) {
						var curronload = window.onload;
						var newonload = function() {
							curronload();
							convert();
						};
						window.onload = newonload;
					} else {
						window.onload = convert;
					}
				}
			</script>';
			
			// Send it to the Header of the page
			$Sender->Head->AddString($JavaScript);
		}
	}
	public function Setup() {
	}
}
// somehow replace the objects with text or something.