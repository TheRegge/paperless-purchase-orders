# Dalton Login Library

*This documentation pertains to version 2.1.3 of the library.*

## Description
This library contains pre-built functions for authenticating a user against Dalton’s Active Directory and/or Whipple Hill and retrieving certain attributes from his/her account. It also provides a wrapper for handling sessions related to the user’s login status.
  
## Installation
Clone this git repository as a submodule into the “libraries” directory of a CodeIgniter webapp.

```
cd </path/to/your/webapp>
git submodule add https://<your BitBucket username>@bitbucket.org/nltl/dalton-login-library.git <path/to/>application/libraries/Dalton_login
```

## Load the Library
`$this->load->library->( 'Dalton_login/dalton_login' );`  
The Dalton Login library must be loaded before its functions can be called. Session handling is called by the library, and doesn’t have to be handled separately. Load the library in a CodeIgniter controller.

## Usage
The following functions need to take place in a CodeIgniter controller.

1.	**Log In**  
`$this->dalton_login->log_in[_ad|_wh|_both|_either|_full]( username, password [, group1, group2, … ] );`  
-- OR --  
`$this->dalton_login->log_in[_ad|_wh|_both|_either|_full]( array( username, password [, group1, group2, … ] ) );`  
Returns TRUE if login is successful. If group names are given, authentication fails unless the user belongs to at least one of the groups given. Note that the group names are case sensitive, and must match exactly their names in Active Directory.  

	***Notes:***  
	`log_in` is retained from previous versions of this library for backwards compatibility It is now an alias for `log_in_ad`.  
	`log_in_ad` authenticates the user against Active Directory.  
	`log_in_wh` authenticates the user against Whipple Hill.  
	`log_in_both` authenticates the user against both Active Directory _and_ Whipple Hill. AD is checked before WH, and selected data from WH (e.g., firstname, lastname, and email) from WH will be returned, rather than the corresponding data from AD.  
	- *Use case 1:* We want to permit login by an AD user belonging to a user group that's not represented in WH, but need their address or phone number, neither of which are in AD.  
	- *Use case 2:* We want to permit login by an AD user belonging to a user group that's not represented in WH, but need their external/prefered email address instead of the one in AD.  
	`log_in_either` authenticates the user against both Active Directory _or_ Whipple Hill. AD is checked before WH, but if the user authenticates successfully against AD, execution does not need to proceed against WH. Therefore, because we don’t know beforehand which database userinfo will draw from, we can only reliably retrieve data fields that are present in both databases.  
	- *Use case 1:* We want to permit login by users (like test accounts) that may not be present in both systems, but don't know beforehand (and don't really care) which database will hold the account.  
	- *Use case 2:* We want to permit login by members of certain AD groups _or_ WH roles.  
	`log_in_full` is similar to `log_in_either`, but execution continues against Whipple Hill even if the user successfully authenticates against Active Directory.
	- *Use case 1:* We want to authenticate users against either database, but need their AD groups _and_ their WH roles or photos, if available.


2.	**Log Out**  
`$this->dalton_login->log_out();`  
Logs out the current user. Does not return any value.

3.	**Get Login Status**  
`$this->dalton_login->get_login_status();`  
Returns TRUE if the user is currently logged in, FALSE if he/she isn’t.

4.	**Get Login Failure**  
`$this->dalton_login->get_login_failure();`  
Returns TRUE if the user’s previous login attempt failed. Use alongside get_login_status() to return appropriate feedback to the user, where necessary or desirable.

5.	**Get User Info**  
`$this->dalton_login->get_user_info( key );`  
Returns user account information for the key given. Keys that are available for any successful login are:  
	- `source`  (The directory from which the following data is retrieved)
	- `username`  
	- `ssid` (Senior Systems ID)  
	- `firstname`  
	- `lastname`  
	- `email`  
Other keys (most imporantly, `groups` and `roles`) are available only once Active Directory or Whipple Hill is called as a data source. If no key is passed, then all session data for the user is returned (including the above keys).

6.	**Check Group/Role Membership**  
`$this->dalton_login->check_[group|role]_membership( array );`  
Given an array of possible Active Directory group or Whipple Hill role names, this function returns an array of the subset of groups and roles to which the currently logged in user belongs. Note that the group/role names are case sensitive, and must match exactly their names in AD or WH. If no matched groups are found, then the function returns FALSE.