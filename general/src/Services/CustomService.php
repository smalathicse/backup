<?php

namespace Drupal\general\Services;

use Drupal\Core\Session\AccountProxy;
/**
 * Class CustomService.
 */
class CustomService {
  /**
   * @var \Drupal\Core\Database\Connection $database
   */
  private $currentUser;

  /**
   * Part of the DependencyInjection magic happening here.
   */
  public function __construct(AccountProxy $currentUser) {
    $this->currentUser = $currentUser;
  }

  /**
   * Returns a a Drupal user as an owner.
   */
  public function getUserName() {
    return $this->currentUser->getDisplayName();
  }
  
  public function getUserRole() {
    $roles = $this->currentUser->getRoles();
	$role = '';
	if($roles[1]) {
		$role = $roles[1];
	}
	return $role;
  }

}