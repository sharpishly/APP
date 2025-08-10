### READ ME ###

## Tik Tok ###

```
$tikTok = new TikTok('your-tiktok-username', 'your-tiktok-password');
$userDetails = $tikTok->login();
$credentials = $tikTok->getCredentials();

echo "User Details:\n";
print_r($userDetails);

echo "\nUser Credentials:\n";
print_r($credentials);

```