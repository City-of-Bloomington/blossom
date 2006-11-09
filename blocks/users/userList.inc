<?php
/*
	$this->userList
*/
	if (userHasRole("Administrator"))
	{
		$addButton = "<button type=\"button\" class=\"addSmall\" onclick=\"document.location.href='".BASE_URL."/users/addUser.php';\">Add</button>";
	}
	else { $addButton = ""; }
?>
<div class="interfaceBox">
	<div class="titleBar"><?php echo $addButton; ?>Users</div>
	<table>
	<?php
		foreach($this->userList as $user)
		{
			if (userHasRole("Administrator"))
			{
				$editButton = "<button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateUser.php?id={$user->getId()}'\">Edit</button>";
				$deleteButton = "<button type=\"button\" class=\"deleteSmall\" onclick=\"deleteConfirmation('deleteUser.php?id={$user->getId()}');\">Delete</button>";
			}
			else
			{
				$editButton = "";
				$deleteButton = "";
			}
			echo "
			<tr><td>{$editButton}{$deleteButton}</td>
				<td>{$user->getUsername()}</td>
				<td>{$user->getFirstname()} {$user->getLastname()}</td>
				<td>{$user->getAuthenticationMethod()}</td>
				<td>
			";
					foreach($user->getRoles() as $role) { echo "$role "; }
			echo "</td></tr>";
		}
	?>
	</table>
</div>