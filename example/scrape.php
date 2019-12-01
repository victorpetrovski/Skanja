<?php
session_start();
include("../vendor/autoload.php");

use \Instagram\Instagram;
use \Instagram\API\Response\Model\User;

$instagram = new \Instagram\Instagram();
$instagram->initFromSavedSession($_SESSION["user_session"]);

$username = $_POST['username'];

try {
    $targetUser = $instagram->getUserByUsername($username);
} catch (Exception $e) {
    echo "Non existing user";
    exit();
}
echo "Scraping from username: " . $username;
echo "<br>";
echo "<br>";

$userInfo = $instagram->getUserInfo($targetUser);

echo "Username: " . $userInfo->getUser()->getBiography();
echo "<br>";
echo "Phone: " . $userInfo->getUser()->getContactPhoneNumber();
echo "<br>";
echo "Email: " . $userInfo->getUser()->getEmail();
echo "<br>";

$totalFollowersCount = $userInfo->getUser()->follower_count;
$followersScraped = 0;
getFollowers($userInfo->getUser());

function getFollowers(User $user, $maxID = null)
{
    global $instagram, $followersScraped, $totalFollowersCount;
    $followersResponse = $instagram->getUserFollowers($user, null);
    foreach ($followersResponse->getFollowers() as $followerUser) {
        $userInfo = $instagram->getUserInfo($followerUser);
        echo $followersScraped++;
        if (!$userInfo->isOk())
            return;
        echo $userInfo->getUser()->getUsername() . " =>" . $userInfo->getUser()->getEmail();
        echo "<br>";
        sleep(2);
    }

    if ($followersScraped < $user->follower_count) {
        getFollowers($user,$followersResponse->getNextMaxId());
    }
}

?>