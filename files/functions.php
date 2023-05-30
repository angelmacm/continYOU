<?php

	function redirectTo($urlArg){

		while (ob_get_status()) 
		{
		    ob_end_clean();
		}

		header( "Location: $urlArg" );
	}

	function hash256($toBeHashArg){
			return hash('sha256', $toBeHashArg);
		}

	function successDialog($titleArg, $contentArg, $idArg, $redirectLinkArg){
		$htmlCode = "<div id='$idArg' class='overlay'>
						<div class='popup'>

							<div class='success-branch-container'>
					        	<p class='popup-title'>$titleArg</p>
					    	</div>
								<a class='close' href='$redirectLinkArg'>&times;</a>
							<div class='success-branch-content'>
								$contentArg.
							</div>
								<div class='success-branch-return'>
							<a href='$redirectLinkArg'>
									<i class='fas fa-check-circle check-mark'></i>
							</a>
								</div>
						</div>
					</div>";
		echo $htmlCode;
	}

	function errorDialog($titleArg, $contentArg, $idArg, $redirectLinkArg){
		$htmlCode = "<div id='$idArg' class='overlay'>
						<div class='popup'>
							<div class='fail-branch-container'>
					        	<p class='popup-title'>$titleArg</p>
					    	</div>
							<div class='fail-branch-content'>
								$contentArg.
							</div>
								<div class='fail-branch-return'>
							<a class='close' href='$redirectLinkArg'>
									<i class='fa-solid fa-circle-xmark cross-mark'></i>
							</a>
								</div>
						</div>
					</div>";
		echo $htmlCode;
	}

	function confirmationDialog($titleArg, $contentArg, $idArg, $redirectLinkArg){
		$htmlCode = "<div id='$idArg' class='overlay'>
						<div class='popup'>
							<div class='fail-branch-container'>
					        	<p class='popup-title'>$titleArg</p>
					    	</div>
							<div class='fail-branch-content'>
								$contentArg.
							</div>
							<div class='confirm-branch-return'>

								<a href='#'>
										<i class='fa-solid fa-circle-xmark cross-mark'></i>
								</a>
								<a href='$redirectLinkArg'>
										<i class='fas fa-check-circle check-mark'></i>
								</a>
							</div>
						</div>
					</div>";
		echo $htmlCode;
	}

	function deleteDialog($titleArg, $contentArg, $idArg, $redirectLinkArg){
		$htmlCode = "<div id='$idArg' class='overlay'>
						<div class='popup'>
							<div class='fail-branch-container'>
					        	<p class='popup-title'>$titleArg</p>
					    	</div>
							<div class='fail-branch-content'>
								$contentArg.
							</div>
							<form method='post' id='deleteForm'></form>
							<div class='confirm-branch-return'>

								<a href='#'>
										<i class='fa-solid fa-circle-xmark cross-mark'></i>
								</a>

								<button type='submit' name='confirmDelete' class='confirm-delete' form='deleteForm'>
										<i class='fas fa-check-circle check-mark'></i>
								</button>
							</div>
						</div>
					</div>";
		echo $htmlCode;
	}

?>


