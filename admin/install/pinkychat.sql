-- ---------------------------------------------------------
--
-- Rainbow PHP Framework - Database Backup Tool
--
--
-- Host Connection Info: localhost via TCP/IP
-- Generation Time: Jul 30, 2019 at 11:16 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.14
--
-- ---------------------------------------------------------


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pinkychat`
--

-- --------------------------------------------------------

--
-- Table structure for table `pinky_admin`
--

CREATE TABLE `pinky_admin` (
  `id` int(11) NOT NULL,
  `user` text DEFAULT NULL,
  `pass` text DEFAULT NULL,
  `name` text DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `reg_date` varchar(255) DEFAULT NULL,
  `reg_ip` varchar(255) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `active_session` text DEFAULT NULL,
  `last_visit` datetime DEFAULT NULL,
  `last_visit_page` text DEFAULT NULL,
  `active_session_id` int(11) DEFAULT NULL
)  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pinky_admin_chat_settings`
--

CREATE TABLE `pinky_admin_chat_settings` (
  `id` int(11) NOT NULL,
  `default_avatar` text DEFAULT NULL,
  `file_share` int(11) DEFAULT NULL,
  `upload_size` int(11) DEFAULT NULL,
  `tone` int(11) DEFAULT NULL,
  `default_tone` int(11) DEFAULT NULL,
  `refresh` int(11) DEFAULT NULL,
  `analytics` int(11) DEFAULT NULL,
  `canned` int(11) DEFAULT NULL,
  `canned_type` int(11) DEFAULT NULL,
  `emoticons` int(11) DEFAULT NULL,
  `beep_sound` int(11) DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_admin_chat_settings`
--

INSERT INTO `pinky_admin_chat_settings` (`id`, `default_avatar`, `file_share`, `upload_size`, `tone`, `default_tone`, `refresh`, `analytics`, `canned`, `canned_type`, `emoticons`, `beep_sound`) VALUES
(1, 'resources/avatars/unknown.png', 1, 5242880, 1, 1, 5000, 600000, 1, 2, 1, 1),
(2, 'resources/avatars/unknown.png', 1, 5242880, 1, 1, 5000, 600000, 1, 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pinky_admin_history`
--

CREATE TABLE `pinky_admin_history` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `logged_time` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `browser` text DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `chat_data` text DEFAULT NULL
)  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pinky_admin_roles`
--

CREATE TABLE `pinky_admin_roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `added_by` varchar(255) NOT NULL,
  `added_date` varchar(255) NOT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_admin_roles`
--

INSERT INTO `pinky_admin_roles` (`id`, `name`, `data`, `added_by`, `added_date`) VALUES
(1, 'Global', '[\\\"all\\\"]', '1', '04/27/2018 01:07:27AM'),
(2, 'CHAT', '[\\\"live-chat\\\",\\\"chat-ajax?::all\\\"]', '1', '03/12/2019 11:03:27PM');

-- --------------------------------------------------------

--
-- Table structure for table `pinky_avatars`
--

CREATE TABLE `pinky_avatars` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `path` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `added_by` text DEFAULT NULL,
  `date` text DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_avatars`
--

INSERT INTO `pinky_avatars` (`id`, `name`, `path`, `status`, `added_by`, `date`) VALUES
(1, 'Default-0', 'resources/avatars/default/0.png', 1, '1', '06/26/2018 02:19:12PM'),
(2, 'Default-1', 'resources/avatars/default/1.png', 1, '1', '06/26/2018 02:25:47PM'),
(3, 'Default-2', 'resources/avatars/default/2.png', 1, '1', '06/26/2018 02:26:00PM'),
(4, 'Default-3', 'resources/avatars/default/3.png', 1, '1', '06/26/2018 02:26:19PM'),
(5, 'Default-4', 'resources/avatars/default/4.png', 1, '1', '06/26/2018 02:26:37PM'),
(6, 'Default-5', 'resources/avatars/default/5.png', 1, '1', '06/26/2018 02:26:51PM'),
(7, 'Default-6', 'resources/avatars/default/6.png', 1, '1', '06/26/2018 02:27:02PM'),
(8, 'Default-7', 'resources/avatars/default/7.png', 1, '1', '06/26/2018 02:27:15PM'),
(9, 'Default-8', 'resources/avatars/default/8.png', 1, '1', '06/26/2018 02:27:25PM'),
(10, 'Default-9', 'resources/avatars/default/9.png', 1, '1', '06/26/2018 02:27:34PM'),
(11, 'Default-10', 'resources/avatars/default/10.png', 1, '1', '06/26/2018 02:27:46PM');

-- --------------------------------------------------------

--
-- Table structure for table `pinky_banned_ip`
--

CREATE TABLE `pinky_banned_ip` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `added_at` varchar(255) DEFAULT NULL,
  `reason` text DEFAULT NULL
)  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pinky_canned_msg`
--

CREATE TABLE `pinky_canned_msg` (
  `id` int(11) NOT NULL,
  `code` text DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `date` varchar(200) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `admin` int(11) DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_canned_msg`
--

INSERT INTO `pinky_canned_msg` (`id`, `code`, `data`, `date`, `status`, `admin`) VALUES
(1, 'balaji', 0x68692062616c616a69, '9th July 2019', 1, 1),
(2, 'hello', 0x48656c6c6f2e20486f77206d617920492061737369737420796f753f, '10th July 2019', 1, 1),
(3, 'hi', 0x48692e20486f77206d617920492061737369737420796f753f, '10th July 2019', 1, 1),
(4, 'still there', 0x41726520796f752074686572653f, '10th July 2019', 1, 1),
(5, 'hello there', 0x41726520796f752074686572653f, '10th July 2019', 1, 1),
(6, 'india', 0x496e6469612c20616c736f206b6e6f776e206173207468652052657075626c6963206f6620496e6469612c206973206120636f756e74727920696e20536f75746820417369612e2049742069732074686520736576656e74682d6c61726765737420636f756e74727920627920617265612c20746865207365636f6e642d6d6f737420706f70756c6f757320636f756e7472792c20616e6420746865206d6f737420706f70756c6f75732064656d6f637261637920696e2074686520776f726c642e, '10th July 2019', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pinky_capthca`
--

CREATE TABLE `pinky_capthca` (
  `id` int(11) NOT NULL,
  `cap_options` text DEFAULT NULL,
  `cap_data` text DEFAULT NULL,
  `cap_type` text DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_capthca`
--

INSERT INTO `pinky_capthca` (`id`, `cap_options`, `cap_data`, `cap_type`) VALUES
(1, '{\\\"contact_page\\\":true,\\\"admin_login_page\\\":false}', '{\\\"recap\\\":{\\\"cap_name\\\":\\\"Google reCAPTCHA\\\",\\\"recap_seckey\\\":\\\"\\\",\\\"recap_sitekey\\\":\\\"\\\"},\\\"phpcap\\\":{\\\"cap_name\\\":\\\"Built-in PHP Image Verification\\\",\\\"mode\\\":\\\"Normal\\\",\\\"allowed\\\":\\\"ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz234567891\\\",\\\"color\\\":\\\"#ffffff\\\",\\\"mul\\\":\\\"yes\\\"}}', 'phpcap');

-- --------------------------------------------------------

--
-- Table structure for table `pinky_chat`
--

CREATE TABLE `pinky_chat` (
  `id` int(11) NOT NULL,
  `date` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `dept` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `operators` text DEFAULT NULL,
  `admin` int(11) DEFAULT NULL,
  `rate` int(11) DEFAULT '0',
  `analytics` int(11) DEFAULT NULL,
  `nowtime` datetime DEFAULT NULL
)  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pinky_chat_history`
--

CREATE TABLE `pinky_chat_history` (
  `id` int(11) NOT NULL,
  `date` varchar(255) DEFAULT NULL,
  `ip` text DEFAULT NULL,
  `sub_msg_id` int(11) DEFAULT NULL,
  `msg` text DEFAULT NULL,
  `data` text DEFAULT NULL,
  `chat_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `notify` int(11) DEFAULT NULL
)  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pinky_chat_settings`
--

CREATE TABLE `pinky_chat_settings` (
  `id` int(11) NOT NULL,
  `chat_title` text DEFAULT NULL,
  `default_avatar` text DEFAULT NULL,
  `file_share` int(11) DEFAULT NULL,
  `upload_size` int(11) DEFAULT NULL,
  `upload_approve` int(11) DEFAULT NULL,
  `tone` int(11) DEFAULT NULL,
  `default_tone` int(11) DEFAULT NULL,
  `refresh` int(11) DEFAULT NULL,
  `side` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `mobile_detect` int(11) DEFAULT NULL,
  `stats` int(11) DEFAULT NULL,
  `msg` int(11) DEFAULT NULL,
  `analytics` int(11) DEFAULT NULL,
  `blacklist` blob DEFAULT NULL,
  `dmsg` int(11) DEFAULT NULL,
  `dname` text DEFAULT NULL,
  `dcontent` text DEFAULT NULL,
  `dlogo` text DEFAULT NULL,
  `emoticons` int(11) DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_chat_settings`
--

INSERT INTO `pinky_chat_settings` (`id`, `chat_title`, `default_avatar`, `file_share`, `upload_size`, `upload_approve`, `tone`, `default_tone`, `refresh`, `side`, `width`, `height`, `mobile_detect`, `stats`, `msg`, `analytics`, `blacklist`, `dmsg`, `dname`, `dcontent`, `dlogo`, `emoticons`) VALUES
(1, 'Chat with Us', 'resources/avatars/unknown.png', 1, 5242880, 0, 1, 1, 5000, 1, 325, 405, 550, 2, 0, 900000, '', 1, 'Admin', 'Hello. How may I assist you?', 'resources/avatars/default/8.png', 1),
(2, 'Chat with Us', 'resources/avatars/unknown.png', 1, 5242880, 0, 1, 1, 5000, 1, 325, 405, 550, 2, 0, 900000, NULL, 1, 'Admin', 'Hello. How may I assist you?', 'resources/avatars/default/8.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pinky_chat_uploads`
--

CREATE TABLE `pinky_chat_uploads` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) DEFAULT NULL,
  `data_id` int(11) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `o_name` text DEFAULT NULL,
  `size` text DEFAULT NULL,
  `uploaded` int(11) DEFAULT NULL,
  `approved` int(11) DEFAULT NULL
)  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pinky_departments`
--

CREATE TABLE `pinky_departments` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `data` text DEFAULT NULL,
  `date` text DEFAULT NULL,
  `des` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_departments`
--

INSERT INTO `pinky_departments` (`id`, `name`, `data`, `date`, `des`, `status`) VALUES
(1, 'General Inquiries', '[\"all\"]', '6th July 2019', NULL, 1),
(2, 'Payment Issues', '[\"all\"]', '6th July 2019', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pinky_emoticon`
--

CREATE TABLE `pinky_emoticon` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `date` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `type` text DEFAULT NULL,
  `data` blob DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_emoticon`
--

INSERT INTO `pinky_emoticon` (`id`, `name`, `added_by`, `date`, `status`, `type`, `data`) VALUES
(1, 'Default Emoticon Pack', 1, '05/01/2018 01:19:45AM', 1, 'sprite', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pinky_emoticon_data`
--

CREATE TABLE `pinky_emoticon_data` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `code` text DEFAULT NULL,
  `data` text DEFAULT NULL,
  `display` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `emoticon` int(11) DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_emoticon_data`
--

INSERT INTO `pinky_emoticon_data` (`id`, `name`, `code`, `data`, `display`, `status`, `emoticon`) VALUES
(1, 'Smile', ':)', '&lt;i title=&quot;Smile&quot; class=&quot;emoticon emoticon-smile&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(2, 'Smile', ':-)', '&lt;i title=&quot;Smile&quot; class=&quot;emoticon emoticon-smile&quot;&gt;&lt;/i&gt;', 0, 1, 1),
(3, 'Smile Big', ':D', '&lt;i title=&quot;Smile Big&quot; class=&quot;emoticon emoticon-smile-big&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(4, 'Smile Big', ':d', '&lt;i title=&quot;Smile Big&quot; class=&quot;emoticon emoticon-smile-big&quot;&gt;&lt;/i&gt;', 0, 1, 1),
(5, 'Sad', ':(', '&lt;i title=&quot;Sad&quot; class=&quot;emoticon emoticon-sad&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(6, 'Sad', ':-(', '&lt;i title=&quot;Sad&quot; class=&quot;emoticon emoticon-sad&quot;&gt;&lt;/i&gt;', 0, 1, 1),
(7, 'Crying', ':((', '&lt;i title=&quot;Crying&quot; class=&quot;emoticon emoticon-crying&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(8, 'Crying', ':-((', '&lt;i title=&quot;Crying&quot; class=&quot;emoticon emoticon-crying&quot;&gt;&lt;/i&gt;', 0, 1, 1),
(9, 'Cool', 'B-)', '&lt;i title=&quot;Cool&quot; class=&quot;emoticon emoticon-cool&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(10, 'Devil', '&gt;:)', '&lt;i title=&quot;Devil&quot; class=&quot;emoticon emoticon-devilish&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(11, 'Surprise', ':O', '&lt;i title=&quot;Surprise&quot; class=&quot;emoticon emoticon-surprise&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(12, 'Surprise', ':-O', '&lt;i title=&quot;Surprise&quot; class=&quot;emoticon emoticon-surprise&quot;&gt;&lt;/i&gt;', 0, 1, 1),
(13, 'Wink', ';)', '&lt;i title=&quot;Wink&quot; class=&quot;emoticon emoticon-wink&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(14, 'Wink', ';-)', '&lt;i title=&quot;Wink&quot; class=&quot;emoticon emoticon-wink&quot;&gt;&lt;/i&gt;', 0, 1, 1),
(15, 'Kiss', ':*', '&lt;i title=&quot;Kiss&quot; class=&quot;emoticon emoticon-kiss&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(16, 'Kiss', ':-*', '&lt;i title=&quot;Kiss&quot; class=&quot;emoticon emoticon-kiss&quot;&gt;&lt;/i&gt;', 0, 1, 1),
(17, 'Monkey', ':(|)', '&lt;i title=&quot;Monkey&quot; class=&quot;emoticon emoticon-monkey&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(18, 'Straight Face', ':-|', '&lt;i title=&quot;Straight Face&quot; class=&quot;emoticon emoticon-plain&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(19, 'Straight Face', ':|', '&lt;i title=&quot;Straight Face&quot; class=&quot;emoticon emoticon-plain&quot;&gt;&lt;/i&gt;', 0, 1, 1),
(20, 'Angel', '0:-)', '&lt;i title=&quot;Angel&quot; class=&quot;emoticon emoticon-angel&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(21, 'Tongue', ':P', '&lt;i title=&quot;Tongue&quot; class=&quot;emoticon emoticon-raspberry&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(22, 'Tongue', ':-P', '&lt;i title=&quot;Tongue&quot; class=&quot;emoticon emoticon-raspberry&quot;&gt;&lt;/i&gt;', 0, 1, 1),
(23, 'Laughing', ':))', '&lt;i title=&quot;Laughing&quot; class=&quot;emoticon emoticon-yahoo&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(24, 'Laughing', ':-))', '&lt;i title=&quot;Laughing&quot; class=&quot;emoticon emoticon-yahoo&quot;&gt;&lt;/i&gt;', 0, 1, 1),
(25, 'Embarrassed', ':&quot;&gt;', '&lt;i title=&quot;Embarrassed&quot; class=&quot;emoticon emoticon-embarrassed&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(26, 'Smug', ':&gt;', '&lt;i title=&quot;Smug&quot; class=&quot;emoticon emoticon-smirk&quot;&gt;&lt;/i&gt;', 1, 1, 1),
(27, 'Smug', ':-&gt;', '&lt;i title=&quot;Smug&quot; class=&quot;emoticon emoticon-smirk&quot;&gt;&lt;/i&gt;', 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pinky_guest_chat`
--

CREATE TABLE `pinky_guest_chat` (
  `id` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `analytics` int(11) DEFAULT NULL,
  `chat_id` int(11) DEFAULT NULL
)  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pinky_interface`
--

CREATE TABLE `pinky_interface` (
  `id` int(11) NOT NULL,
  `theme` text DEFAULT NULL,
  `lang` text DEFAULT NULL,
  `available_languages` text DEFAULT NULL,
  `settings` int(11) DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_interface`
--

INSERT INTO `pinky_interface` (`id`, `theme`, `lang`, `available_languages`, `settings`) VALUES
(1, 'default', 'en', 'a:1:{i:0;a:7:{i:0;s:1:\"1\";i:1;b:1;i:2;s:2:\"en\";i:3;s:7:\"English\";i:4;s:6:\"Balaji\";i:5;b:1;i:6;s:3:\"ltr\";}}', 2);

-- --------------------------------------------------------

--
-- Table structure for table `pinky_invite_chat`
--

CREATE TABLE `pinky_invite_chat` (
  `id` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `chat_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `admin` int(11) DEFAULT NULL
)  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pinky_lang`
--

CREATE TABLE `pinky_lang` (
  `id` int(11) NOT NULL,
  `code` text NOT NULL,
  `default_text` text  DEFAULT NULL,
  `lang_en` text  DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_lang`
--

INSERT INTO `pinky_lang` (`id`, `code`, `default_text`, `lang_en`) VALUES
(1, 'RF1', 'Home', 'Home'),
(2, 'RF2', 'Contact US', 'Contact US'),
(3, 'RF3', 'Image Verification', 'Image Verification'),
(4, 'RF4', 'Your image verification code is wrong!', 'Your image verification code is wrong!'),
(5, 'RF5', 'Submit', 'Submit'),
(6, 'RF6', 'True', 'True'),
(7, 'RF7', 'Try New Site', 'Try New Site'),
(8, 'RF8', 'Captcha', 'Captcha'),
(9, 'RF9', 'We value all the feedbacks received from our customers.', 'We value all the feedbacks received from our customers.'),
(10, 'RF10', 'If you have any queries, comments, suggestions or have anything to talk about.', 'If you have any queries, comments, suggestions or have anything to talk about.'),
(11, 'RF11', 'Please enter your fullname *', 'Please enter your fullname *'),
(12, 'RF12', 'Fullname is required', 'Fullname is required'),
(13, 'RF13', 'Please enter your email *', 'Please enter your email *'),
(14, 'RF14', 'Valid email is required', 'Valid email is required'),
(15, 'RF15', 'Subject is required', 'Subject is required'),
(16, 'RF16', 'Please enter your subject *', 'Please enter your subject *'),
(17, 'RF17', 'Please enter your message *', 'Please enter your message *'),
(18, 'RF18', 'Please leave some message', 'Please leave some message'),
(19, 'RF19', 'Send message', 'Send message'),
(20, 'RF20', 'Alert!', 'Alert!'),
(21, 'RF21', 'Name *', 'Name *'),
(22, 'RF22', 'Email *', 'Email *'),
(23, 'RF23', 'Subject *', 'Subject *'),
(24, 'RF24', 'Message *', 'Message *'),
(25, 'RF25', 'Some fields are missing or empty', 'Some fields are missing or empty'),
(26, 'RF26', 'Guest Visitor', 'Guest Visitor'),
(27, 'RF27', 'Your message has been sent successfully', 'Your message has been sent successfully'),
(28, 'RF28', 'Failed to send your message', 'Failed to send your message'),
(29, 'RF29', 'Please verify your image verification', 'Please verify your image verification'),
(30, 'RF30', 'User Message', 'User Message'),
(31, 'RF31', 'Additional Information', 'Additional Information'),
(32, 'RF32', 'Username', 'Username'),
(33, 'RF33', 'User IP', 'User IP'),
(34, 'RF34', 'Time & Date', 'Time & Date'),
(35, 'RF35', 'Login/Register', 'Login/Register'),
(36, 'RF36', 'You are already logged in', 'You are already logged in'),
(37, 'RF37', 'Activation link successfully sent to your mail id', 'Activation link successfully sent to your mail id'),
(38, 'RF38', 'Email ID already verified!', 'Email ID already verified!'),
(39, 'RF39', 'Email ID not found!', 'Email ID not found!'),
(40, 'RF40', 'Database Error! Contact Support!', 'Database Error! Contact Support!'),
(41, 'RF41', 'New password sent to your mail', 'New password sent to your mail'),
(42, 'RF42', 'You are already logged in', 'You are already logged in'),
(43, 'RF43', 'Login Successful..', 'Login Successful..'),
(44, 'RF44', 'Oh, no your account was banned! Contact Support..', 'Oh, no your account was banned! Contact Support..'),
(45, 'RF45', 'Oh, no account not verified', 'Oh, no account not verified'),
(46, 'RF46', 'Oh, no password is wrong', 'Oh, no password is wrong'),
(47, 'RF47', 'All fields must be filled out!', 'All fields must be filled out!'),
(48, 'RF48', 'Username not found', 'Username not found'),
(49, 'RF49', 'Username already taken', 'Username already taken'),
(50, 'RF50', 'Email ID already registered', 'Email ID already registered'),
(51, 'RF51', 'It looks like your IP has already been used to register an account today!', 'It looks like your IP has already been used to register an account today!'),
(52, 'RF52', 'Username not valid! Username can\'t contain special characters..', 'Username not valid! Username can\'t contain special characters..'),
(53, 'RF53', 'Email ID not valid!', 'Email ID not valid!'),
(54, 'RF54', 'Your account was successfully registered.', 'Your account was successfully registered.'),
(55, 'RF55', 'Redirecting to you index page...', 'Redirecting to you index page...'),
(56, 'RF56', 'An activation email has been sent to your email address, Please also check your Junk/Spam Folders', 'An activation email has been sent to your email address, Please also check your Junk/Spam Folders'),
(57, 'RF57', 'Sign In', 'Sign In'),
(58, 'RF58', 'Sign in using social network', 'Sign in using social network'),
(59, 'RF59', 'Sign in using Facebook', 'Sign in using Facebook'),
(60, 'RF60', 'Sign in using Google', 'Sign in using Google'),
(61, 'RF61', 'Sign in using Twitter', 'Sign in using Twitter'),
(62, 'RF62', 'Facebook', 'Facebook'),
(63, 'RF63', 'Google', 'Google'),
(64, 'RF64', 'Twitter', 'Twitter'),
(65, 'RF65', 'Sign in with your username', 'Sign in with your username'),
(66, 'RF66', 'Username', 'Username'),
(67, 'RF67', 'Password', 'Password'),
(68, 'RF68', 'Forgot Password', 'Forgot Password'),
(69, 'RF69', 'Resend Activation Email', 'Resend Activation Email'),
(70, 'RF70', 'Sign Up', 'Sign Up'),
(71, 'RF71', 'Sign up with your email address', 'Sign up with your email address'),
(72, 'RF72', 'Full Name', 'Full Name'),
(73, 'RF73', 'Email', 'Email'),
(74, 'RF74', 'Enter your email address', 'Enter your email address'),
(75, 'RF75', 'Options:', 'Options:'),
(76, 'RF76', 'Login to your Account', 'Login to your Account'),
(77, 'RF77', 'Register an account', 'Register an account'),
(78, 'RF78', 'Forgot Password', 'Forgot Password'),
(79, 'RF79', 'Resend activation email', 'Resend activation email'),
(80, 'RF80', 'Site is down for maintenance', 'Site is down for maintenance'),
(81, 'RF81', 'We are currently down for maintenance', 'We are currently down for maintenance'),
(82, 'RF82', 'Oops...', 'Oops...'),
(83, 'RF83', 'My Profile', 'My Profile'),
(84, 'RF84', 'Facebook Oauth', 'Facebook Oauth'),
(85, 'RF85', 'Google Oauth', 'Google Oauth'),
(86, 'RF86', 'Twitter Oauth', 'Twitter Oauth'),
(87, 'RF87', 'There was an error on Oauth service!', 'There was an error on Oauth service!'),
(88, 'RF88', 'There was a problem performing this request', 'There was a problem performing this request'),
(89, 'RF89', 'Log In', 'Log In'),
(90, 'RF90', 'Account already verified...', 'Account already verified...'),
(91, 'RF91', 'Something Went Wrong! Contact Support!', 'Something Went Wrong! Contact Support!'),
(92, 'RF92', 'Verification code is wrong..', 'Verification code is wrong..'),
(93, 'RF93', 'Account verified successfully. You can login now..', 'Account verified successfully. You can login now..'),
(94, 'RF94', 'New Password updated successfully!', 'New Password updated successfully!'),
(95, 'RF95', 'Current password is wrong!', 'Current password is wrong!'),
(96, 'RF96', 'New password & Retype password field can\'t matched!', 'New password & Retype password field can\'t matched!'),
(97, 'RF97', 'Sorry, your file is too large.', 'Sorry, your file is too large.'),
(98, 'RF98', 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.', 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.'),
(99, 'RF99', 'Sorry, there was an error uploading your file.', 'Sorry, there was an error uploading your file.'),
(100, 'RF100', 'File is not an image.', 'File is not an image.'),
(101, 'RF101', 'Profile details was successfully updated!', 'Profile details was successfully updated!'),
(102, 'RF102', 'Unknown', 'Unknown'),
(103, 'RF103', 'Dashboard', 'Dashboard'),
(104, 'RF104', 'Logout', 'Logout'),
(105, 'RF105', 'Restricted words found on your domain name', 'Restricted words found on your domain name'),
(106, 'RF106', 'Continue', 'Continue'),
(107, 'RF107', 'About Us', 'About Us'),
(108, 'RF108', 'Contact Info', 'Contact Info'),
(109, 'RF109', 'Navigation', 'Navigation'),
(110, 'RF110', 'Profile', 'Profile'),
(111, 'RF111', 'Update Information', 'Update Information'),
(112, 'RF112', 'Change Password', 'Change Password'),
(113, 'RF113', 'User Logo', 'User Logo'),
(114, 'RF114', 'Email ID', 'Email ID'),
(115, 'RF115', 'Registered At', 'Registered At'),
(116, 'RF116', 'User Country', 'User Country'),
(117, 'RF117', 'Membership', 'Membership'),
(118, 'RF118', 'Free', 'Free'),
(119, 'RF119', 'Personal Information:', 'Personal Information:'),
(120, 'RF120', 'First Name', 'First Name'),
(121, 'RF121', 'Last Name', 'Last Name'),
(122, 'RF122', 'Company', 'Company'),
(123, 'RF123', 'Address Line 1', 'Address Line 1'),
(124, 'RF124', 'Address Line 2', 'Address Line 2'),
(125, 'RF125', 'City', 'City'),
(126, 'RF126', 'State', 'State'),
(127, 'RF127', 'Country', 'Country'),
(128, 'RF128', 'Post Code', 'Post Code'),
(129, 'RF129', 'Telephone', 'Telephone'),
(130, 'RF130', 'General Information:', 'General Information:'),
(131, 'RF131', 'Avatar:', 'Avatar:'),
(132, 'RF132', 'Upload a new avatar: (JPEG 180x180px)', 'Upload a new avatar: (JPEG 180x180px)'),
(133, 'RF133', 'Region / State', 'Region / State'),
(134, 'RF134', 'Current Password', 'Current Password'),
(135, 'RF135', 'New Password', 'New Password'),
(136, 'RF136', 'Retype Password', 'Retype Password'),
(137, 'RF137', 'Change Password', 'Change Password'),
(138, 'RF138', 'Retype your new password', 'Retype your new password'),
(139, 'RF139', 'Enter your new password', 'Enter your new password'),
(140, 'RF140', 'Enter your current password', 'Enter your current password'),
(141, 'RF141', 'Enter your postal code', 'Enter your postal code'),
(142, 'RF142', 'Enter your city', 'Enter your city'),
(143, 'RF143', 'Address line 2 (optional)', 'Address line 2 (optional)'),
(144, 'RF144', 'Enter your home address', 'Enter your home address'),
(145, 'RF145', 'Enter your phone no.', 'Enter your phone no.'),
(146, 'RF146', 'Enter your company name (optional)', 'Enter your company name (optional)'),
(147, 'RF147', 'Enter your last name', 'Enter your last name'),
(148, 'RF148', 'Enter your first name', 'Enter your first name'),
(149, 'RF149', 'Enter your full name', 'Enter your full name'),
(150, 'RF150', 'Enter your user name', 'Enter your user name'),
(151, 'RF151', 'Enter your email id', 'Enter your email id'),
(152, 'RF152', 'Domain is banned for following reason:', 'Domain is banned for following reason:'),
(153, 'CH1', 'Uploaded file size is big', 'Uploaded file size is big'),
(154, 'CH2', 'You are not authorized to access this page!', 'You are not authorized to access this page!'),
(155, 'CH3', 'Operator denied the file!', 'Operator denied the file!'),
(156, 'CH4', 'Chat Transcript', 'Chat Transcript'),
(157, 'CH5', 'Print Chat', 'Print Chat'),
(158, 'CH6', 'Enquiry', 'Enquiry'),
(159, 'CH7', 'Unable to send chat message!', 'Unable to send chat message!'),
(160, 'CH8', 'Failed to start chat!', 'Failed to start chat!'),
(161, 'CH9', 'Page Not Found!', 'Page Not Found!'),
(162, 'CH10', 'Widget Test', 'Widget Test'),
(163, 'CH11', 'Widget Test Inline', 'Widget Test Inline'),
(164, 'CH12', 'Chat under maintenance mode', 'Chat under maintenance mode'),
(165, 'CH13', 'Loading...', 'Loading...'),
(166, 'CH14', 'Chat closed by user', 'Chat closed by user'),
(167, 'CH15', 'Chat transcript sent successfully', 'Chat transcript sent successfully'),
(168, 'CH16', 'Failed to sent chat transcript', 'Failed to sent chat transcript'),
(169, 'CH17', 'Minimize', 'Minimize'),
(170, 'CH18', 'Chat Window Close', 'Chat Window Close'),
(171, 'CH19', 'Full Screen Minimize', 'Full Screen Minimize'),
(172, 'CH20', 'Introduce yourself to start chat', 'Introduce yourself to start chat'),
(173, 'CH21', 'Your Name', 'Your Name'),
(174, 'CH22', 'Your Email ID', 'Your Email ID'),
(175, 'CH23', 'Please enter a valid name.', 'Please enter a valid name.'),
(176, 'CH24', 'Please enter a valid email.', 'Please enter a valid email.'),
(177, 'CH25', 'What can we help with?', 'What can we help with?'),
(178, 'CH26', 'Choose your inquiry type.', 'Choose your inquiry type.'),
(179, 'CH27', 'Start Chatting', 'Start Chatting'),
(180, 'CH28', 'Type your message...', 'Type your message...'),
(181, 'CH29', 'Insert an emoji', 'Insert an emoji'),
(182, 'CH30', 'Send a File', 'Send a File'),
(183, 'CH31', 'Options', 'Options'),
(184, 'CH32', 'Toggle fullscreen', 'Toggle fullscreen'),
(185, 'CH33', 'Mute Sound', 'Mute Sound'),
(186, 'CH34', 'Print Chat', 'Print Chat'),
(187, 'CH35', 'Update Details', 'Update Details'),
(188, 'CH36', 'End Chat', 'End Chat'),
(189, 'CH37', 'Name:', 'Name:'),
(190, 'CH38', 'Email:', 'Email:'),
(191, 'CH39', 'Save', 'Save'),
(192, 'CH40', 'Cancel', 'Cancel'),
(193, 'CH41', 'Are you sure that you want to end chat?', 'Are you sure that you want to end chat?'),
(194, 'CH42', 'Chat Ended', 'Chat Ended'),
(195, 'CH43', 'How do you rate your customer service experience?', 'How do you rate your customer service experience?'),
(196, 'CH44', 'Poor', 'Poor'),
(197, 'CH45', 'Average', 'Average'),
(198, 'CH46', 'Great', 'Great'),
(199, 'CH47', 'Thanks for your rating!', 'Thanks for your rating!'),
(200, 'CH48', 'Start New Chat', 'Start New Chat');

INSERT INTO `pinky_lang` (`id`, `code`, `default_text`, `lang_en`) VALUES
(201, 'CH49', 'Send Transcript', 'Send Transcript'),
(202, 'CH50', 'All staffs are offline', 'All staffs are offline'),
(203, 'CH51', 'Your Message', 'Your Message'),
(204, 'CH52', 'Please enter your message.', 'Please enter your message.'),
(205, 'CH53', 'Send', 'Send'),
(206, 'CH54', 'Thank you for contacting us. One of our representatives will be in contact with you shortly regarding your inquiry.', 'Thank you for contacting us. One of our representatives will be in contact with you shortly regarding your inquiry.'),
(207, 'CH55', 'Try Again', 'Try Again'),
(208, 'CH56', 'Error Loading', 'Error Loading'),
(209, 'CH57', 'Upload Failed!', 'Upload Failed!'),
(210, 'CH58', 'Download', 'Download'),
(211, 'CH59', 'Waiting for approval...', 'Waiting for approval...'),
(212, 'CH60', 'Uploading...', 'Uploading...'),
(213, 'CH61', 'just now', 'just now'),
(214, 'CH62', 'seconds ago', 'seconds ago'),
(215, 'CH63', 'minute ago', 'minute ago'),
(216, 'CH64', 'minutes ago', 'minutes ago'),
(217, 'CH65', 'hour ago', 'hour ago'),
(218, 'CH66', 'hours ago', 'hours ago'),
(219, 'CH67', 'day ago', 'day ago'),
(220, 'CH68', 'days ago', 'days ago'),
(221, 'CH69', 'week ago', 'week ago'),
(222, 'CH70', 'weeks ago', 'weeks ago'),
(223, 'CH71', 'month ago', 'month ago'),
(224, 'CH72', 'months ago', 'months ago'),
(225, 'CH73', 'year ago', 'year ago'),
(226, 'CH74', 'years ago', 'years ago'),
(227, 'CH75', 'Unknown', 'Unknown'),
(228, 'CH76', 'Live Chat', 'Live Chat'),
(229, 'CH77', 'Control panel', 'Control panel'),
(230, 'CH78', 'Admin', 'Admin'),
(231, 'CH79', 'Online Users', 'Online Users'),
(232, 'CH80', 'Online Operators', 'Online Operators'),
(233, 'CH81', 'Details', 'Details'),
(234, 'CH82', 'Notification Sounds', 'Notification Sounds'),
(235, 'CH83', 'Canned Messages', 'Canned Messages'),
(236, 'CH84', 'User profile picture', 'User profile picture'),
(237, 'CH85', 'Email', 'Email'),
(238, 'CH86', 'Role', 'Role'),
(239, 'CH87', 'Access', 'Access'),
(240, 'CH88', 'Chat ID', 'Chat ID'),
(241, 'CH89', 'Location', 'Location'),
(242, 'CH90', 'Browser', 'Browser'),
(243, 'CH91', 'Platform', 'Platform'),
(244, 'CH92', 'IP', 'IP'),
(245, 'CH93', 'User Agent', 'User Agent'),
(246, 'CH94', 'Invite Operator', 'Invite Operator'),
(247, 'CH95', 'Screen', 'Screen'),
(248, 'CH96', 'Visitors Path', 'Visitors Path'),
(249, 'CH97', 'It\'s not your department!', 'It\'s not your department!'),
(250, 'CH98', 'Error: Something went wrong! Try to refresh the page!', 'Error: Something went wrong! Try to refresh the page!'),
(251, 'CH99', 'Already chat request sent!', 'Already chat request sent!'),
(252, 'CH100', 'Invitation sent!', 'Invitation sent!'),
(253, 'CH101', 'Sending invitation failed!', 'Sending invitation failed!'),
(254, 'CH102', 'Select Operator!', 'Select Operator!'),
(255, 'CH103', 'Invite Operator?', 'Invite Operator?'),
(256, 'CH104', 'Invite user?', 'Invite user?'),
(257, 'CH105', 'Are you sure that you want to invite user to talk?', 'Are you sure that you want to invite user to talk?'),
(258, 'CH106', 'Invite', 'Invite'),
(259, 'CH107', 'Unable to start chat!', 'Unable to start chat!'),
(260, 'CH108', 'Join chat?', 'Join chat?'),
(261, 'CH109', 'User chatting with another operator, are you want to join?', 'User chatting with another operator, are you want to join?'),
(262, 'CH110', 'Join', 'Join'),
(263, 'CH111', 'You can\'t chat yourself!', 'You can\'t chat yourself!'),
(264, 'CH112', 'Are you sure?', 'Are you sure?'),
(265, 'CH113', 'Leave Talk?', 'Leave Talk?'),
(266, 'CH114', 'Are you sure that you want to leave from talk?', 'Are you sure that you want to leave from talk?'),
(267, 'CH115', 'Failed to close!', 'Failed to close!'),
(268, 'CH116', 'File:', 'File:'),
(269, 'CH117', 'Size:', 'Size:'),
(270, 'CH118', 'Preview', 'Preview'),
(271, 'CH119', 'Chat', 'Chat'),
(272, 'CH120', 'Users', 'Users'),
(273, 'CH121', 'Operators', 'Operators'),
(274, 'CH122', 'Admin role not found!', 'Admin role not found!'),
(275, 'CH123', 'Password is Wrong. Try Again!', 'Password is Wrong. Try Again!'),
(276, 'CH124', 'Login Failed. Try Again!', 'Login Failed. Try Again!'),
(277, 'CH125', 'All fields must be filled in!', 'All fields must be filled in!'),
(278, 'CH126', 'Login Successful. Redirecting to dashboard page wait...', 'Login Successful. Redirecting to dashboard page wait...'),
(279, 'CH127', 'Admin Section', 'Admin Section'),
(280, 'CH128', 'Sign in to start your session', 'Sign in to start your session'),
(281, 'CH129', 'Remember Me', 'Remember Me'),
(282, 'CH130', 'I forgot my password', 'I forgot my password'),
(283, 'CH131', 'After reset, delete the &quot;reset.php&quot; file!', 'After reset, delete the &quot;reset.php&quot; file!'),
(284, 'CH132', 'Forget Password', 'Forget Password'),
(285, 'CH133', 'Ended', 'Ended'),
(286, 'CH134', 'Waiting', 'Waiting'),
(287, 'CH135', 'Chatting', 'Chatting'),
(288, 'CH136', 'Delete the admin password reset file found on script root directory!', 'Delete the admin password reset file found on script root directory!'),
(289, 'CH137', 'Users Online', 'Users Online'),
(290, 'CH138', 'Today Chats', 'Today Chats'),
(291, 'CH139', 'Today Pageviews', 'Today Pageviews'),
(292, 'CH140', 'Today Unique Visitors', 'Today Unique Visitors'),
(293, 'CH141', 'Analytics', 'Analytics'),
(294, 'CH142', 'Not enough Data', 'Not enough Data'),
(295, 'CH143', 'Recent Chats', 'Recent Chats'),
(296, 'CH144', 'Chat Started', 'Chat Started'),
(297, 'CH145', 'Department', 'Department'),
(298, 'CH146', 'Status', 'Status'),
(299, 'CH147', 'Admin Login History', 'Admin Login History'),
(300, 'CH148', 'Login Date', 'Login Date'),
(301, 'CH149', 'Server Information', 'Server Information'),
(302, 'CH150', 'Server IP', 'Server IP'),
(303, 'CH151', 'Server Disk Space', 'Server Disk Space'),
(304, 'CH152', 'Free Disk Space', 'Free Disk Space'),
(305, 'CH153', 'Disk Space used by Script', 'Disk Space used by Script'),
(306, 'CH154', 'Memory Used', 'Memory Used'),
(307, 'CH155', 'Current CPU Load', 'Current CPU Load'),
(308, 'CH156', 'PHP Version', 'PHP Version'),
(309, 'CH157', 'MySQL Version', 'MySQL Version'),
(310, 'CH158', 'Database Size', 'Database Size'),
(311, 'CH159', 'Latest New Users', 'Latest New Users'),
(312, 'CH160', 'Registered', 'Registered'),
(313, 'CH161', 'Script Update', 'Script Update'),
(314, 'CH162', 'Your Version', 'Your Version'),
(315, 'CH163', 'Latest Version', 'Latest Version'),
(316, 'CH164', 'Update', 'Update'),
(317, 'CH165', 'Currently no update available!', 'Currently no update available!'),
(318, 'CH166', 'Latest News', 'Latest News'),
(319, 'CH167', 'Unique Visitorss', 'Unique Visitorss'),
(320, 'CH168', 'Page View', 'Page View'),
(321, 'CH169', 'Manage Site', 'Manage Site'),
(322, 'CH170', 'Basic Settings', 'Basic Settings'),
(323, 'CH171', 'Unable to save site information!', 'Unable to save site information!'),
(324, 'CH172', 'Site information saved successfully', 'Site information saved successfully'),
(325, 'CH173', 'Site Name', 'Site Name'),
(326, 'CH174', 'Enter site name', 'Enter site name'),
(327, 'CH175', 'Enter app name', 'Enter app name'),
(328, 'CH176', 'Application Name', 'Application Name'),
(329, 'CH177', 'HTML Application Name &lt;small&gt;(Name displayed at Admin Panel)&lt;/small&gt;', 'HTML Application Name &lt;small&gt;(Name displayed at Admin Panel)&lt;/small&gt;'),
(330, 'CH178', 'Enter html app name', 'Enter html app name'),
(331, 'CH179', 'Admin Email ID', 'Admin Email ID'),
(332, 'CH180', 'Enter email id of admin', 'Enter email id of admin'),
(333, 'CH181', 'Copyright Text', 'Copyright Text'),
(334, 'CH182', 'Enter your copyright info', 'Enter your copyright info'),
(335, 'CH183', 'Website Address', 'Website Address'),
(336, 'CH184', 'Base URL', 'Base URL'),
(337, 'CH185', 'HTTPS Redirect', 'HTTPS Redirect'),
(338, 'CH186', 'Force WWW in URL', 'Force WWW in URL'),
(339, 'CH187', 'Save Settings', 'Save Settings'),
(340, 'CH188', 'Maintenance Settings', 'Maintenance Settings'),
(341, 'CH189', 'Chat Online / Offline', 'Chat Online / Offline'),
(342, 'CH190', 'Maintenance settings saved successfully', 'Maintenance settings saved successfully'),
(343, 'CH191', 'Enable maintenance mode (Users can\'t able to access the chat!).', 'Enable maintenance mode (Users can\'t able to access the chat!).'),
(344, 'CH192', 'Maintenance Reason', 'Maintenance Reason'),
(345, 'CH193', 'Note: Administrators still have access the full chat functionality!', 'Note: Administrators still have access the full chat functionality!'),
(346, 'CH194', 'Enter your reason', 'Enter your reason'),
(347, 'CH195', 'Captcha Protection', 'Captcha Protection'),
(348, 'CH196', 'Captcha Settings', 'Captcha Settings'),
(349, 'CH197', 'Unable to save Captcha settings', 'Unable to save Captcha settings'),
(350, 'CH198', 'Captcha settings saved successfully', 'Captcha settings saved successfully'),
(351, 'CH199', 'Captcha protection for following pages:', 'Captcha protection for following pages:'),
(352, 'CH200', 'Which pages need image verifications?', 'Which pages need image verifications?'),
(353, 'CH201', 'Select Capthca Service', 'Select Capthca Service'),
(354, 'CH202', 'Secret Key', 'Secret Key'),
(355, 'CH203', 'Site Key', 'Site Key'),
(356, 'CH204', 'Enter your', 'Enter your'),
(357, 'CH205', 'Built-in PHP Image Verification', 'Built-in PHP Image Verification'),
(358, 'CH206', 'Difficulty type', 'Difficulty type'),
(359, 'CH207', 'Easy', 'Easy'),
(360, 'CH208', 'Normal', 'Normal'),
(361, 'CH209', 'Tough', 'Tough'),
(362, 'CH210', 'Allowed characters', 'Allowed characters'),
(363, 'CH211', 'Captcha text color', 'Captcha text color'),
(364, 'CH212', 'Multiple background images', 'Multiple background images'),
(365, 'CH213', 'Yes', 'Yes'),
(366, 'CH214', 'No', 'No'),
(367, 'CH215', 'Enter your characters', 'Enter your characters'),
(368, 'CH216', 'Ban IP Address', 'Ban IP Address'),
(369, 'CH217', 'Add User IP to Ban', 'Add User IP to Ban'),
(370, 'CH218', 'Unable to save settings!', 'Unable to save settings!'),
(371, 'CH219', 'IP added to database successfully.', 'IP added to database successfully.'),
(372, 'CH220', 'IP is not valid!', 'IP is not valid!'),
(373, 'CH221', 'IP Address to Ban:', 'IP Address to Ban:'),
(374, 'CH222', 'Enter user ip to ban', 'Enter user ip to ban'),
(375, 'CH223', 'Reason:', 'Reason:'),
(376, 'CH224', '(Optional)', '(Optional)'),
(377, 'CH225', 'Reason to ban?', 'Reason to ban?'),
(378, 'CH226', 'Note: Banned IP\'s can\'t able to access your chat!', 'Note: Banned IP\'s can\'t able to access your chat!'),
(379, 'CH227', 'Add', 'Add'),
(380, 'CH228', 'Recently banned IP\'s', 'Recently banned IP\'s'),
(381, 'CH229', 'Banned IP', 'Banned IP'),
(382, 'CH230', 'Banned Reason', 'Banned Reason'),
(383, 'CH231', 'Added Date', 'Added Date'),
(384, 'CH232', 'Delete', 'Delete'),
(385, 'CH233', 'Empty!', 'Empty!'),
(386, 'CH234', 'Are you sure you want to delete this item?', 'Are you sure you want to delete this item?'),
(387, 'CH235', 'Adblock Detection', 'Adblock Detection'),
(388, 'CH236', 'Detect &amp; Ban - Ad Blocking Users', 'Detect &amp; Ban - Ad Blocking Users'),
(389, 'CH237', 'Unable to save &quot;Ad block&quot; settings', 'Unable to save &quot;Ad block&quot; settings'),
(390, 'CH238', 'Ad block settings saved successfully', 'Ad block settings saved successfully'),
(391, 'CH239', 'Adblock notification type', 'Adblock notification type'),
(392, 'CH240', 'Enable ad-blocker detection and protection system', 'Enable ad-blocker detection and protection system'),
(393, 'CH241', 'Redirect to custom adblock notification page', 'Redirect to custom adblock notification page'),
(394, 'CH242', 'Dialog box with close button (User can continue to access website)', 'Dialog box with close button (User can continue to access website)'),
(395, 'CH243', 'Dialog box without close button (User can\'t continue to access website)', 'Dialog box without close button (User can\'t continue to access website)'),
(396, 'CH244', 'Redirect Link', 'Redirect Link'),
(397, 'CH245', 'Type link', 'Type link'),
(398, 'CH246', 'Type title', 'Type title'),
(399, 'CH247', 'Type description', 'Type description'),
(400, 'CH248', 'Dialog Box - Title', 'Dialog Box - Title');

INSERT INTO `pinky_lang` (`id`, `code`, `default_text`, `lang_en`) VALUES
(401, 'CH249', 'Dialog Box - Message', 'Dialog Box - Message'),
(402, 'CH250', 'Get Widget Code', 'Get Widget Code'),
(403, 'CH251', 'Embed Chat Widget', 'Embed Chat Widget'),
(404, 'CH252', 'Bottom Widget', 'Bottom Widget'),
(405, 'CH253', 'Inline Widget', 'Inline Widget'),
(406, 'CH254', 'Widget Language', 'Widget Language'),
(407, 'CH255', 'To chat with your visitors, you\'ll need to embed the widget on your website.', 'To chat with your visitors, you\'ll need to embed the widget on your website.'),
(408, 'CH256', 'Chat Settings', 'Chat Settings'),
(409, 'CH257', 'Customize Widget', 'Customize Widget'),
(410, 'CH258', 'Copy to Clipboard', 'Copy to Clipboard'),
(411, 'CH259', 'Inline Widget Code', 'Inline Widget Code'),
(412, 'CH260', 'Widget Code', 'Widget Code'),
(413, 'CH261', 'How to use:', 'How to use:'),
(414, 'CH262', 'Copy the following script and insert it into your website\'s HTML source code between the &lt;code&gt;&amp;#x3C;head&amp;#x3E;&lt;/code&gt; or before closing &lt;code&gt;&amp;#x3C;/body&amp;#x3E;&lt;/code&gt;  tags.', 'Copy the following script and insert it into your website\'s HTML source code between the &lt;code&gt;&amp;#x3C;head&amp;#x3E;&lt;/code&gt; or before closing &lt;code&gt;&amp;#x3C;/body&amp;#x3E;&lt;/code&gt;  tags.'),
(415, 'CH263', 'Text has been copied to clipboard', 'Text has been copied to clipboard'),
(416, 'CH264', 'Success', 'Success'),
(417, 'CH265', 'Unable to copy!', 'Unable to copy!'),
(418, 'CH266', 'Go Back', 'Go Back'),
(419, 'CH267', 'Export Chat', 'Export Chat'),
(420, 'CH268', 'Name', 'Name'),
(421, 'CH269', 'Date', 'Date'),
(422, 'CH270', 'Stats', 'Stats'),
(423, 'CH271', 'Rating', 'Rating'),
(424, 'CH272', 'Actions', 'Actions'),
(425, 'CH273', 'Delete Selected', 'Delete Selected'),
(426, 'CH274', 'Chat History', 'Chat History'),
(427, 'CH275', 'Chat List', 'Chat List'),
(428, 'CH276', 'Good', 'Good'),
(429, 'CH277', 'No Rating', 'No Rating'),
(430, 'CH278', 'Owner', 'Owner'),
(431, 'CH279', 'Message List', 'Message List'),
(432, 'CH280', 'Add Canned Message', 'Add Canned Message'),
(433, 'CH281', 'Unable to add!', 'Unable to add!'),
(434, 'CH282', 'Canned message added successfully', 'Canned message added successfully'),
(435, 'CH283', 'Unable to update!', 'Unable to update!'),
(436, 'CH284', 'Canned message updated successfully', 'Canned message updated successfully'),
(437, 'CH285', 'Edit Canned Message', 'Edit Canned Message'),
(438, 'CH286', 'Shortcut', 'Shortcut'),
(439, 'CH287', 'Message', 'Message'),
(440, 'CH288', 'Enter shortcut name', 'Enter shortcut name'),
(441, 'CH289', 'Enter canned message', 'Enter canned message'),
(442, 'CH290', 'Disabled', 'Disabled'),
(443, 'CH291', 'Enabled', 'Enabled'),
(444, 'CH292', 'Code', 'Code'),
(445, 'CH293', 'Avatars', 'Avatars'),
(446, 'CH294', 'Manage Avatars', 'Manage Avatars'),
(447, 'CH295', 'Status updated successfully', 'Status updated successfully'),
(448, 'CH296', 'Add Avatar', 'Add Avatar'),
(449, 'CH297', 'Avatar Details', 'Avatar Details'),
(450, 'CH298', 'Edit Avatar', 'Edit Avatar'),
(451, 'CH299', 'Avatar settings updated successfully', 'Avatar settings updated successfully'),
(452, 'CH300', 'Avatar added successfully', 'Avatar added successfully'),
(453, 'CH301', 'Unable to add avatar!', 'Unable to add avatar!'),
(454, 'CH302', 'Add New Avatar', 'Add New Avatar'),
(455, 'CH303', 'Enter your avatar name', 'Enter your avatar name'),
(456, 'CH304', 'Avatar Name', 'Avatar Name'),
(457, 'CH305', 'Avatar URL', 'Avatar URL'),
(458, 'CH306', 'Enter avatar URL', 'Enter avatar URL'),
(459, 'CH307', 'ID', 'ID'),
(460, 'CH308', 'Added By', 'Added By'),
(461, 'CH309', 'Edit', 'Edit'),
(462, 'CH310', 'Emoticons', 'Emoticons'),
(463, 'CH311', 'Manage Emoticon Packs', 'Manage Emoticon Packs'),
(464, 'CH312', 'Icon status updated successfully', 'Icon status updated successfully'),
(465, 'CH313', 'Display status updated successfully', 'Display status updated successfully'),
(466, 'CH314', 'Create New Emoticon Pack', 'Create New Emoticon Pack'),
(467, 'CH315', 'Emoticon Pack Details', 'Emoticon Pack Details'),
(468, 'CH316', 'Edit Emoticon Pack', 'Edit Emoticon Pack'),
(469, 'CH317', 'Emoticon Pack settings updated successfully', 'Emoticon Pack settings updated successfully'),
(470, 'CH318', 'Emoticon Pack created successfully', 'Emoticon Pack created successfully'),
(471, 'CH319', 'Unable to add emoticon!', 'Unable to add emoticon!'),
(472, 'CH320', 'Add Emoticon', 'Add Emoticon'),
(473, 'CH321', 'Edit Emoticon', 'Edit Emoticon'),
(474, 'CH322', 'Emoticon updated successfully', 'Emoticon updated successfully'),
(475, 'CH323', 'Emoticon added successfully', 'Emoticon added successfully'),
(476, 'CH324', 'Add Icon', 'Add Icon'),
(477, 'CH325', 'Emoticon Name', 'Emoticon Name'),
(478, 'CH326', 'Emoticon Code', 'Emoticon Code'),
(479, 'CH327', 'Enter emoticon code', 'Enter emoticon code'),
(480, 'CH328', 'Enter emoticon name', 'Enter emoticon name'),
(481, 'CH329', 'Emoticon URL', 'Emoticon URL'),
(482, 'CH330', 'Enter emoticon URL', 'Enter emoticon URL'),
(483, 'CH331', 'Emoticon HTML Code', 'Emoticon HTML Code'),
(484, 'CH332', 'Enter emoticon icon HTML code', 'Enter emoticon icon HTML code'),
(485, 'CH333', 'Display emoticon to users', 'Display emoticon to users'),
(486, 'CH334', 'Display', 'Display'),
(487, 'CH335', 'Pack Name', 'Pack Name'),
(488, 'CH336', 'Enter your emoticons pack name', 'Enter your emoticons pack name'),
(489, 'CH337', 'Emoticons Type', 'Emoticons Type'),
(490, 'CH338', 'Separate Images', 'Separate Images'),
(491, 'CH339', 'CSS Image Sprites (Group)', 'CSS Image Sprites (Group)'),
(492, 'CH340', 'Font Icons', 'Font Icons'),
(493, 'CH341', 'CSS Data', 'CSS Data'),
(494, 'CH342', 'Enter any addtional CSS codes here', 'Enter any addtional CSS codes here'),
(495, 'CH343', 'Create', 'Create'),
(496, 'CH344', 'Settings', 'Settings'),
(497, 'CH345', 'View Icons', 'View Icons'),
(498, 'CH346', 'Default', 'Default'),
(499, 'CH347', 'Notifications Tones', 'Notifications Tones'),
(500, 'CH348', 'Manage Notification Tones', 'Manage Notification Tones'),
(501, 'CH349', 'Add Notification Tone', 'Add Notification Tone'),
(502, 'CH350', 'Notification Tone Details', 'Notification Tone Details'),
(503, 'CH351', 'Notification tone settings updated successfully', 'Notification tone settings updated successfully'),
(504, 'CH352', 'Notification tone added successfully', 'Notification tone added successfully'),
(505, 'CH353', 'Unable to add tone!', 'Unable to add tone!'),
(506, 'CH354', 'Add New Notification Tone', 'Add New Notification Tone'),
(507, 'CH355', 'Tone Name', 'Tone Name'),
(508, 'CH356', 'Enter your tone name', 'Enter your tone name'),
(509, 'CH357', 'Tone URL', 'Tone URL'),
(510, 'CH358', 'Enter tone URL', 'Enter tone URL'),
(511, 'CH359', 'Playback', 'Playback'),
(512, 'CH360', 'Manage Departments', 'Manage Departments'),
(513, 'CH361', 'Departments', 'Departments'),
(514, 'CH362', 'Add Department', 'Add Department'),
(515, 'CH363', 'Department added successfully', 'Department added successfully'),
(516, 'CH364', 'Update Department', 'Update Department'),
(517, 'CH365', 'Department updated successfully', 'Department updated successfully'),
(518, 'CH366', 'Enter department name', 'Enter department name'),
(519, 'CH367', 'Enter about department', 'Enter about department'),
(520, 'CH368', 'Description', 'Description'),
(521, 'CH369', 'Restricted', 'Restricted'),
(522, 'CH370', 'All', 'All'),
(523, 'CH371', 'Provide access for following admins', 'Provide access for following admins'),
(524, 'CH372', 'Which admins are allowed to access?', 'Which admins are allowed to access?'),
(525, 'CH373', 'Chat Settings (Clients)', 'Chat Settings (Clients)'),
(526, 'CH374', 'Your server upload limit', 'Your server upload limit'),
(527, 'CH375', 'Settings restored successfully', 'Settings restored successfully'),
(528, 'CH376', 'Settings restore failed', 'Settings restore failed'),
(529, 'CH377', 'Chat settings saved successfully', 'Chat settings saved successfully'),
(530, 'CH378', 'Chat Settings (Admin)', 'Chat Settings (Admin)'),
(531, 'CH379', 'Chat Header Title', 'Chat Header Title'),
(532, 'CH380', 'Enter chat title', 'Enter chat title'),
(533, 'CH381', 'Default Avatar', 'Default Avatar'),
(534, 'CH382', 'Enter avatar link', 'Enter avatar link'),
(535, 'CH383', 'Turn Off', 'Turn Off'),
(536, 'CH384', 'Turn On', 'Turn On'),
(537, 'CH385', 'Available notification tones', 'Available notification tones'),
(538, 'CH386', 'Widget width (in pixels)', 'Widget width (in pixels)'),
(539, 'CH387', 'Enter width (px)', 'Enter width (px)'),
(540, 'CH388', 'Widget height (in pixels)', 'Widget height (in pixels)'),
(541, 'CH389', 'Enter height (px)', 'Enter height (px)'),
(542, 'CH390', 'Mobile version breakpoint', 'Mobile version breakpoint'),
(543, 'CH391', 'Enter mobile version breakpoint width', 'Enter mobile version breakpoint width'),
(544, 'CH392', 'Chat widget side', 'Chat widget side'),
(545, 'CH393', 'Left', 'Left'),
(546, 'CH394', 'Right', 'Right'),
(547, 'CH395', 'File Sharing', 'File Sharing'),
(548, 'CH396', 'Allow', 'Allow'),
(549, 'CH397', 'Maximum uploaded file size (MB)', 'Maximum uploaded file size (MB)'),
(550, 'CH398', 'Enter upload size', 'Enter upload size'),
(551, 'CH399', 'Auto approve file uploads', 'Auto approve file uploads'),
(552, 'CH400', 'Chatting refresh rate (ms)', 'Chatting refresh rate (ms)'),
(553, 'CH401', 'Enter refresh rate', 'Enter refresh rate'),
(554, 'CH402', 'User analytics refresh rate (ms)', 'User analytics refresh rate (ms)'),
(555, 'CH403', 'Enter analytics refresh rate', 'Enter analytics refresh rate'),
(556, 'CH404', 'Minimized Chat Window', 'Minimized Chat Window'),
(557, 'CH405', 'Maximized Chat Window', 'Maximized Chat Window'),
(558, 'CH406', 'Show widget automatically', 'Show widget automatically'),
(559, 'CH407', 'Default Message', 'Default Message'),
(560, 'CH408', 'Print initial default message', 'Print initial default message'),
(561, 'CH409', 'Default message admin name', 'Default message admin name'),
(562, 'CH410', 'Enter admin name', 'Enter admin name'),
(563, 'CH411', 'Default message admin logo', 'Default message admin logo'),
(564, 'CH412', 'Enter logo link', 'Enter logo link'),
(565, 'CH413', 'Default message content', 'Default message content'),
(566, 'CH414', 'Enter message content', 'Enter message content'),
(567, 'CH415', 'Pages where the widget should not be displayed?', 'Pages where the widget should not be displayed?'),
(568, 'CH416', 'Widget Blacklist (one URL per line)', 'Widget Blacklist (one URL per line)'),
(569, 'CH417', 'Are you sure you want to restore default settings?', 'Are you sure you want to restore default settings?'),
(570, 'CH418', 'Reset to default', 'Reset to default'),
(571, 'CH419', 'Customize Widget (Colors, Text Size etc..)', 'Customize Widget (Colors, Text Size etc..)'),
(572, 'CH420', 'Canned Message Method', 'Canned Message Method'),
(573, 'CH421', 'AJAX Method', 'AJAX Method'),
(574, 'CH422', 'Offline Method', 'Offline Method'),
(575, 'CH423', 'Domain License', 'Domain License'),
(576, 'CH424', 'License Change', 'License Change'),
(577, 'CH425', 'Item Purchase Code', 'Item Purchase Code'),
(578, 'CH426', 'Registered Domain Name', 'Registered Domain Name'),
(579, 'CH427', 'Registered Link', 'Registered Link'),
(580, 'CH428', 'License Key', 'License Key'),
(581, 'CH429', 'Reset Domain Name', 'Reset Domain Name'),
(582, 'CH430', 'Compress database backup file using Gzip', 'Compress database backup file using Gzip'),
(583, 'CH431', 'Automatically backup database using Cron Job', 'Automatically backup database using Cron Job'),
(584, 'CH432', 'Backup Daily', 'Backup Daily'),
(585, 'CH433', 'Backup Weekly', 'Backup Weekly'),
(586, 'CH434', 'Backup Monthly', 'Backup Monthly'),
(587, 'CH435', 'Backup Now', 'Backup Now'),
(588, 'CH436', 'Backup &amp; Download', 'Backup &amp; Download'),
(589, 'CH437', 'DB Backup List', 'DB Backup List'),
(590, 'CH438', 'Filename', 'Filename'),
(591, 'CH439', 'Backup Date', 'Backup Date'),
(592, 'CH440', 'Database Backup', 'Database Backup'),
(593, 'CH441', 'DB Backup Settings', 'DB Backup Settings'),
(594, 'CH442', 'Database settings saved successfully', 'Database settings saved successfully'),
(595, 'CH443', 'Database backup saved Successfully', 'Database backup saved Successfully'),
(596, 'CH444', 'Database backup failed', 'Database backup failed'),
(597, 'CH445', 'Backup file deleted Successfully', 'Backup file deleted Successfully'),
(598, 'CH446', 'Unable to remove backup file', 'Unable to remove backup file'),
(599, 'CH447', 'PHP Info Viewer', 'PHP Info Viewer'),
(600, 'CH448', 'PHP Information', 'PHP Information');

INSERT INTO `pinky_lang` (`id`, `code`, `default_text`, `lang_en`) VALUES
(601, 'CH449', 'Error Log File Viewer', 'Error Log File Viewer'),
(602, 'CH450', 'Error Log', 'Error Log'),
(603, 'CH451', 'Error Log cleared successfully!', 'Error Log cleared successfully!'),
(604, 'CH452', 'Error log is empty!', 'Error log is empty!'),
(605, 'CH453', 'Clear Error Log', 'Clear Error Log'),
(606, 'CH454', 'Error Log includes warnings, fatal errors and notice messages.', 'Error Log includes warnings, fatal errors and notice messages.'),
(607, 'CH455', 'Note', 'Note'),
(608, 'CH456', 'Clear Cron Log', 'Clear Cron Log'),
(609, 'CH457', 'Intervals between cron jobs is 10 to 30 miniutes(Maxmium). Don\'t setup Cron Job more than 30 minutes!', 'Intervals between cron jobs is 10 to 30 miniutes(Maxmium). Don\'t setup Cron Job more than 30 minutes!'),
(610, 'CH458', 'Cron Job Path', 'Cron Job Path'),
(611, 'CH459', 'Cron Execution Log', 'Cron Execution Log'),
(612, 'CH460', 'Cron Job Viewer', 'Cron Job Viewer'),
(613, 'CH461', 'Cron Job', 'Cron Job'),
(614, 'CH462', 'Cron Log cleared successfully!', 'Cron Log cleared successfully!'),
(615, 'CH463', 'Cron log is empty!', 'Cron log is empty!'),
(616, 'CH464', 'Manage Addons', 'Manage Addons'),
(617, 'CH465', 'Install Add-on', 'Install Add-on'),
(618, 'CH466', 'ZipArchive Extension', 'ZipArchive Extension'),
(619, 'CH467', 'Not Found', 'Not Found'),
(620, 'CH468', 'Found', 'Found'),
(621, 'CH469', 'Writable', 'Writable'),
(622, 'CH470', 'Not Writable', 'Not Writable'),
(623, 'CH471', 'Directory', 'Directory'),
(624, 'CH472', 'Your server PHP upload limit', 'Your server PHP upload limit'),
(625, 'CH473', 'Sorry, only ZIP, ZIPX and ADDONPK files are allowed.', 'Sorry, only ZIP, ZIPX and ADDONPK files are allowed.'),
(626, 'CH474', 'Adddon was successfully uploaded', 'Adddon was successfully uploaded'),
(627, 'CH475', 'Copying Theme Files to', 'Copying Theme Files to'),
(628, 'CH476', 'Addons Installer is not detected!', 'Addons Installer is not detected!'),
(629, 'CH477', 'Not compatible add-on!', 'Not compatible add-on!'),
(630, 'CH478', 'Shop Addons', 'Shop Addons'),
(631, 'CH479', 'Pinky Chat Addons', 'Pinky Chat Addons'),
(632, 'CH480', 'Install Addons', 'Install Addons'),
(633, 'CH481', 'Manually Uploaded Files', 'Manually Uploaded Files'),
(634, 'CH482', 'Install', 'Install'),
(635, 'CH483', 'Select a addon package to install', 'Select a addon package to install'),
(636, 'CH484', 'Upload &amp; Install', 'Upload &amp; Install'),
(637, 'CH485', 'Don\'t upload unknown addons and make sure it is downloaded from authorized site.', 'Don\'t upload unknown addons and make sure it is downloaded from authorized site.'),
(638, 'CH486', 'Read instruction before processing automatic installation.', 'Read instruction before processing automatic installation.'),
(639, 'CH487', 'Price', 'Price'),
(640, 'CH488', 'Link', 'Link'),
(641, 'CH489', 'Buy Now', 'Buy Now'),
(642, 'CH490', 'All actions are irreversible!', 'All actions are irreversible!'),
(643, 'CH491', 'Warning', 'Warning'),
(644, 'CH492', 'Select your action', 'Select your action'),
(645, 'CH493', 'Clean up all temporary directories', 'Clean up all temporary directories'),
(646, 'CH494', 'Clear all analytics data', 'Clear all analytics data'),
(647, 'CH495', 'Clear all admin login history data', 'Clear all admin login history data'),
(648, 'CH496', 'Clear all users accounts', 'Clear all users accounts'),
(649, 'CH497', 'Clear older than 2 months chats', 'Clear older than 2 months chats'),
(650, 'CH498', 'Clear older than 6 months chats', 'Clear older than 6 months chats'),
(651, 'CH499', 'Clear older than 1 year chats', 'Clear older than 1 year chats'),
(652, 'CH500', 'Clear all chats &amp; history data', 'Clear all chats &amp; history data'),
(653, 'CH501', 'Are you sure you want to process?', 'Are you sure you want to process?'),
(654, 'CH502', 'Process', 'Process'),
(655, 'CH503', 'Miscellaneous', 'Miscellaneous'),
(656, 'CH504', 'Miscellaneous Task', 'Miscellaneous Task'),
(657, 'CH505', 'All temporary directories data has been deleted successfully', 'All temporary directories data has been deleted successfully'),
(658, 'CH506', 'All analytics data has been successfully cleared', 'All analytics data has been successfully cleared'),
(659, 'CH507', 'Admin logged history has been successfully cleared', 'Admin logged history has been successfully cleared'),
(660, 'CH508', 'All users accounts has been deleted successfully', 'All users accounts has been deleted successfully'),
(661, 'CH509', 'All chats data has been deleted successfully', 'All chats data has been deleted successfully'),
(662, 'CH510', 'Older than 2 months chats are cleared successfully', 'Older than 2 months chats are cleared successfully'),
(663, 'CH511', 'Older than 6 months chats are cleared successfully', 'Older than 6 months chats are cleared successfully'),
(664, 'CH512', 'Older than 1 year chats are cleared successfully', 'Older than 1 year chats are cleared successfully'),
(665, 'CH513', 'Manage Users', 'Manage Users'),
(666, 'CH514', 'User List', 'User List'),
(667, 'CH515', 'New user added successfully!', 'New user added successfully!'),
(668, 'CH516', 'Export', 'Export'),
(669, 'CH517', 'Joined Date', 'Joined Date'),
(670, 'CH518', 'Last Active', 'Last Active'),
(671, 'CH519', 'Administrators Accounts', 'Administrators Accounts'),
(672, 'CH520', 'Admin Details', 'Admin Details'),
(673, 'CH521', 'Logo updated successfully!', 'Logo updated successfully!'),
(674, 'CH522', 'Failed to update logo!', 'Failed to update logo!'),
(675, 'CH523', 'New Password saved successfully!', 'New Password saved successfully!'),
(676, 'CH524', 'Unable to save admin details!', 'Unable to save admin details!'),
(677, 'CH525', 'Old admin panel password is wrong!', 'Old admin panel password is wrong!'),
(678, 'CH526', 'Your password and confirmation password do not match!', 'Your password and confirmation password do not match!'),
(679, 'CH527', 'Overview', 'Overview'),
(680, 'CH528', 'Update Account', 'Update Account'),
(681, 'CH529', 'Change Avatar', 'Change Avatar'),
(682, 'CH530', 'Admin Logo', 'Admin Logo'),
(683, 'CH531', 'Admin Name', 'Admin Name'),
(684, 'CH532', 'Admin User ID', 'Admin User ID'),
(685, 'CH533', 'Admin Group', 'Admin Group'),
(686, 'CH534', 'Page Access', 'Page Access'),
(687, 'CH535', 'Registration Date', 'Registration Date'),
(688, 'CH536', 'Registration IP', 'Registration IP'),
(689, 'CH537', 'Last Login Date', 'Last Login Date'),
(690, 'CH538', 'Last Active IP', 'Last Active IP'),
(691, 'CH539', 'Admin ID', 'Admin ID'),
(692, 'CH540', 'Enter your admin name', 'Enter your admin name'),
(693, 'CH541', 'Old Password', 'Old Password'),
(694, 'CH542', 'Enter your old admin panel password', 'Enter your old admin panel password'),
(695, 'CH543', 'Retype the new password', 'Retype the new password'),
(696, 'CH544', 'Select logo to upload', 'Select logo to upload'),
(697, 'CH545', 'Upload Image', 'Upload Image'),
(698, 'CH546', 'Add New Admin', 'Add New Admin'),
(699, 'CH547', 'Username (Email ID)', 'Username (Email ID)'),
(700, 'CH548', 'Admin Group (Privilege)', 'Admin Group (Privilege)'),
(701, 'CH549', 'Create Account', 'Create Account'),
(702, 'CH550', 'Retype your password for confirmation', 'Retype your password for confirmation'),
(703, 'CH551', 'Type your password', 'Type your password'),
(704, 'CH552', 'Type admin name', 'Type admin name'),
(705, 'CH553', 'Type admin email id', 'Type admin email id'),
(706, 'CH554', 'Administrator Accounts', 'Administrator Accounts'),
(707, 'CH555', 'Admin List', 'Admin List'),
(708, 'CH556', 'Update Admin', 'Update Admin'),
(709, 'CH557', 'Edit Admin Account', 'Edit Admin Account'),
(710, 'CH558', 'Admin account updated successfully', 'Admin account updated successfully'),
(711, 'CH559', 'Unable to update admin account!', 'Unable to update admin account!'),
(712, 'CH560', 'Email ID already exists!', 'Email ID already exists!'),
(713, 'CH561', 'Admin account created successfully', 'Admin account created successfully'),
(714, 'CH562', 'Unable to create admin account!', 'Unable to create admin account!'),
(715, 'CH563', 'New Admin', 'New Admin'),
(716, 'CH564', 'Create Admin Account', 'Create Admin Account'),
(717, 'CH565', 'Privilege Management', 'Privilege Management'),
(718, 'CH566', 'Admin Groups', 'Admin Groups'),
(719, 'CH567', 'Add New Privileges', 'Add New Privileges'),
(720, 'CH568', 'Role Details', 'Role Details'),
(721, 'CH569', 'Unable to update admin role!', 'Unable to update admin role!'),
(722, 'CH570', 'Admin role updated successfully', 'Admin role updated successfully'),
(723, 'CH571', 'Unable to create admin role!', 'Unable to create admin role!'),
(724, 'CH572', 'Admin role created successfully', 'Admin role created successfully'),
(725, 'CH573', 'Edit Privileges', 'Edit Privileges'),
(726, 'CH574', 'Update Roles', 'Update Roles'),
(727, 'CH575', 'Create New Role', 'Create New Role'),
(728, 'CH576', 'Role Name', 'Role Name'),
(729, 'CH577', 'Access Type', 'Access Type'),
(730, 'CH578', 'Provide access for following pages', 'Provide access for following pages'),
(731, 'CH579', 'Type role name', 'Type role name'),
(732, 'CH580', 'Global (All)', 'Global (All)'),
(733, 'CH581', 'Which pages are allowed to access?', 'Which pages are allowed to access?'),
(734, 'CH582', 'Update Admin Role', 'Update Admin Role'),
(735, 'CH583', 'Create Admin Role', 'Create Admin Role'),
(736, 'CH584', 'Created on', 'Created on'),
(737, 'CH585', 'Edit Language', 'Edit Language'),
(738, 'CH586', 'Delete Language', 'Delete Language'),
(739, 'CH587', 'Mail Settings', 'Mail Settings'),
(740, 'CH588', 'General Settings', 'General Settings'),
(741, 'CH589', 'Mail information saved successfully', 'Mail information saved successfully'),
(742, 'CH590', 'Select your Mail Protocol', 'Select your Mail Protocol'),
(743, 'CH591', 'PHP Mail', 'PHP Mail'),
(744, 'CH592', 'SMTP', 'SMTP'),
(745, 'CH593', 'SMTP Information', 'SMTP Information'),
(746, 'CH594', 'SMTP Host', 'SMTP Host'),
(747, 'CH595', 'SMTP Auth', 'SMTP Auth'),
(748, 'CH596', 'SMTP Port', 'SMTP Port'),
(749, 'CH597', 'SMTP Username', 'SMTP Username'),
(750, 'CH598', 'SMTP Password', 'SMTP Password'),
(751, 'CH599', 'SMTP Secure Socket', 'SMTP Secure Socket'),
(752, 'CH600', 'TLS', 'TLS'),
(753, 'CH601', 'SSL', 'SSL'),
(754, 'CH602', 'Enter smtp password', 'Enter smtp password'),
(755, 'CH603', 'Enter smtp username', 'Enter smtp username'),
(756, 'CH604', 'Enter smtp port', 'Enter smtp port'),
(757, 'CH605', 'Enter smtp host', 'Enter smtp host'),
(758, 'CH606', 'False', 'False'),
(759, 'CH607', 'Update Templates', 'Update Templates'),
(760, 'CH608', 'Short Codes also supported inside mail templates!', 'Short Codes also supported inside mail templates!'),
(761, 'CH609', 'Password Reset - Mail Template', 'Password Reset - Mail Template'),
(762, 'CH610', 'Subject', 'Subject'),
(763, 'CH611', 'Mail Content', 'Mail Content'),
(764, 'CH612', 'Replacement Codes', 'Replacement Codes'),
(765, 'CH613', 'Returns your site name', 'Returns your site name'),
(766, 'CH614', 'Returns customers name', 'Returns customers name'),
(767, 'CH615', 'Returns customers username', 'Returns customers username'),
(768, 'CH616', 'Returns new password', 'Returns new password'),
(769, 'CH617', 'Returns customers mail id', 'Returns customers mail id'),
(770, 'CH618', 'Email Templates', 'Email Templates'),
(771, 'CH619', 'Templates', 'Templates'),
(772, 'CH620', 'Mail updated successfully', 'Mail updated successfully'),
(773, 'CH621', 'Send Email', 'Send Email'),
(774, 'CH622', 'Send Email to Customers', 'Send Email to Customers'),
(775, 'CH623', 'Select your option', 'Select your option'),
(776, 'CH624', 'Custom Email', 'Custom Email'),
(777, 'CH625', 'Email to Customers', 'Email to Customers'),
(778, 'CH626', 'Type your customer name (autocomplete)', 'Type your customer name (autocomplete)'),
(779, 'CH627', 'Subject:', 'Subject:'),
(780, 'CH628', 'To:', 'To:'),
(781, 'CH629', 'Discard', 'Discard'),
(782, 'CH630', 'Analytics Overview', 'Analytics Overview'),
(783, 'CH631', 'Direct', 'Direct'),
(784, 'CH632', 'Remove', 'Remove'),
(785, 'CH633', 'Collapse', 'Collapse'),
(786, 'CH634', 'Hourly Traffic', 'Hourly Traffic'),
(787, 'CH635', 'Pages', 'Pages'),
(788, 'CH636', 'Pageviews', 'Pageviews'),
(789, 'CH637', 'Percentage', 'Percentage'),
(790, 'CH638', 'Countries', 'Countries'),
(791, 'CH639', 'Sessions', 'Sessions'),
(792, 'CH640', 'Operating Systems', 'Operating Systems'),
(793, 'CH641', 'Referer', 'Referer'),
(794, 'CH642', 'Referral', 'Referral'),
(795, 'CH643', 'Who\'s Online', 'Who\'s Online'),
(796, 'CH644', 'Active Users', 'Active Users'),
(797, 'CH645', 'No users online', 'No users online'),
(798, 'CH646', 'Customer', 'Customer'),
(799, 'CH647', 'Last Page Visited', 'Last Page Visited'),
(800, 'CH648', 'Last Click', 'Last Click'),
(801, 'CH649', 'Visitor Log', 'Visitor Log'),
(802, 'CH650', 'Hits', 'Hits'),
(803, 'CH651', 'Last Visit', 'Last Visit'),
(804, 'CH652', 'Page Views', 'Page Views'),
(805, 'CH653', 'Entry', 'Entry'),
(806, 'CH654', 'Screen Resolution', 'Screen Resolution'),
(807, 'CH655', 'Visitor Details', 'Visitor Details'),
(808, 'CH656', 'OS/Browser Details', 'OS/Browser Details'),
(809, 'CH657', 'Pages Viewed', 'Pages Viewed'),
(810, 'CH658', 'Manage Interface', 'Manage Interface'),
(811, 'CH659', 'Default Interface Settings', 'Default Interface Settings'),
(812, 'CH660', 'Interface settings saved successfully', 'Interface settings saved successfully'),
(813, 'CH661', 'Default Template', 'Default Template'),
(814, 'CH662', 'Default Language', 'Default Language'),
(815, 'CH663', 'Change your default template', 'Change your default template'),
(816, 'CH664', 'Change your default language', 'Change your default language'),
(817, 'CH665', 'Manage Themes', 'Manage Themes'),
(818, 'CH666', 'All Themes', 'All Themes'),
(819, 'CH667', 'Selected theme applied successfully.', 'Selected theme applied successfully.'),
(820, 'CH668', 'Clone Theme', 'Clone Theme');
INSERT INTO `pinky_lang` (`id`, `code`, `default_text`, `lang_en`) VALUES
(821, 'CH669', 'Creating database column failed!', 'Creating database column failed!'),
(822, 'CH670', 'Cloning template database settings are failed!', 'Cloning template database settings are failed!'),
(823, 'CH671', 'Completed!', 'Completed!'),
(824, 'CH672', 'The directory', 'The directory'),
(825, 'CH673', 'isn\'t writable.', 'isn\'t writable.'),
(826, 'CH674', 'Copy Failed', 'Copy Failed'),
(827, 'CH675', 'Go to Manage Themes', 'Go to Manage Themes'),
(828, 'CH676', 'Theme Name', 'Theme Name'),
(829, 'CH677', 'Theme Directory Name', 'Theme Directory Name'),
(830, 'CH678', 'Your Website Link', 'Your Website Link'),
(831, 'CH679', '(Only Alphanumeric characters)', '(Only Alphanumeric characters)'),
(832, 'CH680', 'Copyright', 'Copyright'),
(833, 'CH681', 'Required directory permissions', 'Required directory permissions'),
(834, 'CH682', 'Type theme name', 'Type theme name'),
(835, 'CH683', 'Type directory name', 'Type directory name'),
(836, 'CH684', 'Type theme description', 'Type theme description'),
(837, 'CH685', 'Type author name', 'Type author name'),
(838, 'CH686', 'Type author email', 'Type author email'),
(839, 'CH687', 'Type author website link', 'Type author website link'),
(840, 'CH688', 'Apply', 'Apply'),
(841, 'CH689', 'Are you want to make default template?', 'Are you want to make default template?'),
(842, 'CH690', 'Clone', 'Clone'),
(843, 'CH691', 'Active', 'Active'),
(844, 'CH692', 'By', 'By'),
(845, 'CH693', 'Language Editor', 'Language Editor'),
(846, 'CH694', 'Available Languages', 'Available Languages'),
(847, 'CH695', 'Language code missing!', 'Language code missing!'),
(848, 'CH696', 'Import Language File', 'Import Language File'),
(849, 'CH697', 'Sorry, only LBAK and LDATA files are allowed.', 'Sorry, only LBAK and LDATA files are allowed.'),
(850, 'CH698', 'Language file was successfully imported', 'Language file was successfully imported'),
(851, 'CH699', 'Sorry, language already exist.', 'Sorry, language already exist.'),
(852, 'CH700', 'Language Data Error!', 'Language Data Error!'),
(853, 'CH701', 'Sorry, you can\'t able to disable default language!', 'Sorry, you can\'t able to disable default language!'),
(854, 'CH702', 'Sorry, you can\'t able to delete default language!', 'Sorry, you can\'t able to delete default language!'),
(855, 'CH703', 'Create New Language', 'Create New Language'),
(856, 'CH704', 'Add New Custom String', 'Add New Custom String'),
(857, 'CH705', 'Language data updated successfully.', 'Language data updated successfully.'),
(858, 'CH706', 'Language code not valid!', 'Language code not valid!'),
(859, 'CH707', 'Add Custom Text', 'Add Custom Text'),
(860, 'CH708', 'Import Language', 'Import Language'),
(861, 'CH709', 'Backup Language', 'Backup Language'),
(862, 'CH710', 'Language Name', 'Language Name'),
(863, 'CH711', 'Type language name', 'Type language name'),
(864, 'CH712', 'Language Code', 'Language Code'),
(865, 'CH713', 'Type language code', 'Type language code'),
(866, 'CH714', 'Sort Order', 'Sort Order'),
(867, 'CH715', 'Text Direction', 'Text Direction'),
(868, 'CH716', 'Left to Right', 'Left to Right'),
(869, 'CH717', 'Right to Left', 'Right to Left'),
(870, 'CH718', 'You can\'t able to disable default language. First change default language from here', 'You can\'t able to disable default language. First change default language from here'),
(871, 'CH719', 'Interface Settings', 'Interface Settings'),
(872, 'CH720', 'Language Data', 'Language Data'),
(873, 'CH721', 'Language String', 'Language String'),
(874, 'CH722', 'Default String', 'Default String'),
(875, 'CH723', 'UID', 'UID'),
(876, 'CH724', 'Type your name', 'Type your name'),
(877, 'CH725', 'Reference', 'Reference'),
(878, 'CH726', '2 Letter', '2 Letter'),
(879, 'CH727', 'Create Language File', 'Create Language File'),
(880, 'CH728', 'Type your text', 'Type your text'),
(881, 'CH729', 'Select language file to upload', 'Select language file to upload'),
(882, 'CH730', 'Import', 'Import'),
(883, 'CH731', 'Include Custom Language Strings ?', 'Include Custom Language Strings ?'),
(884, 'CH732', 'Author', 'Author'),
(885, 'CH733', 'Preview your website with this language', 'Preview your website with this language'),
(886, 'CH734', 'Saving...', 'Saving...'),
(887, 'CH735', 'Saved', 'Saved'),
(888, 'CH736', 'MAIN NAVIGATION', 'MAIN NAVIGATION'),
(889, 'CH737', 'Site Settings', 'Site Settings'),
(890, 'CH738', 'Maintenance', 'Maintenance'),
(891, 'CH739', 'Chat App', 'Chat App'),
(892, 'CH740', 'Chat AJAX Calls', 'Chat AJAX Calls'),
(893, 'CH741', 'Notification Tunes', 'Notification Tunes'),
(894, 'CH742', 'Settings (Front End)', 'Settings (Front End)'),
(895, 'CH743', 'Settings (Back End)', 'Settings (Back End)'),
(896, 'CH744', 'Visitors Log', 'Visitors Log'),
(897, 'CH745', 'Email Setup', 'Email Setup'),
(898, 'CH746', 'Administrators', 'Administrators'),
(899, 'CH747', 'Admin Accounts', 'Admin Accounts'),
(900, 'CH748', 'Interface', 'Interface'),
(901, 'CH749', 'Addons', 'Addons'),
(902, 'CH750', 'Process Installation', 'Process Installation'),
(903, 'CH751', 'Error Log Viewer', 'Error Log Viewer'),
(904, 'CH752', 'ADVANCED FEATURES (BETA)', 'ADVANCED FEATURES (BETA)'),
(905, 'CH753', 'File Manager', 'File Manager'),
(906, 'CH754', 'Admin Chat', 'Admin Chat'),
(907, 'CH755', 'View', 'View'),
(908, 'CH756', 'Date range must not greater than one year between two dates!', 'Date range must not greater than one year between two dates!'),
(909, 'CH757', 'Upload Approved!', 'Upload Approved!'),
(910, 'CH758', 'Upload not found!', 'Upload not found!'),
(911, 'CH759', 'Uploaded file size is big!', 'Uploaded file size is big!'),
(912, 'CH760', 'Chat closed by operator', 'Chat closed by operator'),
(913, 'CH761', 'Operator', 'Operator'),
(914, 'CH762', 'left', 'left'),
(915, 'CH763', 'Operator already on talk!', 'Operator already on talk!'),
(916, 'CH764', 'No operators online!', 'No operators online!'),
(917, 'CH765', 'No users online!', 'No users online!'),
(918, 'CH766', 'joined into chat', 'joined into chat'),
(919, 'CH767', 'Chat handled by', 'Chat handled by'),
(920, 'CH768', 'You don\'t have sufficient privileges!', 'You don\'t have sufficient privileges!'),
(921, 'CH769', 'Unknown Theme', 'Unknown Theme'),
(922, 'CH770', 'Theme Builder Not Found!', 'Theme Builder Not Found!'),
(923, 'CH771', 'Default Theme Settings', 'Default Theme Settings'),
(924, 'CH772', 'General', 'General'),
(925, 'CH773', 'Add Custom Stylesheet', 'Add Custom Stylesheet'),
(926, 'CH774', 'Chat Widget', 'Chat Widget'),
(927, 'CH775', 'Chat Background Color', 'Chat Background Color'),
(928, 'CH776', 'Enter your background color', 'Enter your background color'),
(929, 'CH777', 'Chat content font Size (in pixels)', 'Chat content font Size (in pixels)'),
(930, 'CH778', 'Enter content size (px)', 'Enter content size (px)'),
(931, 'CH779', 'Text Color', 'Text Color'),
(932, 'CH780', 'Enter your text color', 'Enter your text color'),
(933, 'CH781', 'Chat Border Color', 'Chat Border Color'),
(934, 'CH782', 'Enter your border color', 'Enter your border color'),
(935, 'CH783', 'Username font Size (in pixels)', 'Username font Size (in pixels)'),
(936, 'CH784', 'Enter username size (px)', 'Enter username size (px)'),
(937, 'CH785', 'Gradient Header Background', 'Gradient Header Background'),
(938, 'CH786', 'Header Color', 'Header Color'),
(939, 'CH787', 'Gradient', 'Gradient'),
(940, 'CH788', 'Enter your color', 'Enter your color'),
(941, 'CH789', 'Enter header label size (px)', 'Enter header label size (px)'),
(942, 'CH790', 'Header label font Size (in pixels)', 'Header label font Size (in pixels)'),
(943, 'CH791', 'Primary Button', 'Primary Button'),
(944, 'CH792', 'Border Color', 'Border Color'),
(945, 'CH793', 'Primary Button (Hover)', 'Primary Button (Hover)'),
(946, 'CH794', 'Background Color', 'Background Color'),
(947, 'CH795', 'Blue Button', 'Blue Button'),
(948, 'CH796', 'Grey Button', 'Grey Button'),
(949, 'CH797', 'Green Button', 'Green Button'),
(950, 'CH798', 'Orange Button', 'Orange Button'),
(951, 'CH799', 'Red Button', 'Red Button'),
(952, 'CH800', 'Enter custom stylesheet code', 'Enter custom stylesheet code'),
(953, 'CH801', 'Themes Preview', 'Themes Preview'),
(954, 'CH802', 'Failed', 'Failed'),
(955, 'CH803', 'Page', 'Page'),
(956, 'CH804', 'User Details', 'User Details'),
(957, 'CH805', 'Unknown File', 'Unknown File'),
(958, 'CH806', 'file types are allowed.', 'file types are allowed.'),
(959, 'CH807', 'Sorry, only', 'Sorry, only'),
(960, 'CH808', 'Approve', 'Approve'),
(961, 'CH809', 'Disapprove', 'Disapprove'),
(962, 'CH810', 'Contact Us Page', 'Contact Us Page'),
(963, 'CH811', 'Administrator Login Page', 'Administrator Login Page'),
(964, 'CH812', 'Operator Details', 'Operator Details'),
(965, 'CH813', 'File Attachment', 'File Attachment'),
(966, 'CH814', 'Upload Status', 'Upload Status'),
(967, 'CH815', 'When you\'ve few canned messages then select &quot;Offline method&quot; otherwise &quot;AJAX method&quot; recommended', 'When you\'ve few canned messages then select &quot;Offline method&quot; otherwise &quot;AJAX method&quot; recommended'),
(968, 'CH816', 'You don\'t have sufficient permissions to access this page!', 'You don\'t have sufficient permissions to access this page!'),
(969, 'CH817', 'Or sign in as a different user', 'Or sign in as a different user'),
(970, 'CH818', 'User Image', 'User Image'),
(971, 'CH819', 'Addon Installation Log File', 'Addon Installation Log File'),
(972, 'CH820', 'Addon Installation Completed with Error!', 'Addon Installation Completed with Error!'),
(973, 'CH821', 'Addon Installation Failed!', 'Addon Installation Failed!'),
(974, 'CH822', 'Addon Installation Completed!', 'Addon Installation Completed!'),
(975, 'CH823', 'Goto', 'Goto'),
(976, 'CH824', 'Your session has expired. Please log in again!', 'Your session has expired. Please log in again!'),
(977, 'CH825', 'Available emoticon packs', 'Available emoticon packs');

-- --------------------------------------------------------

--
-- Table structure for table `pinky_mail`
--

CREATE TABLE `pinky_mail` (
  `id` int(11) NOT NULL,
  `smtp_host` text DEFAULT NULL,
  `smtp_username` text DEFAULT NULL,
  `smtp_password` text DEFAULT NULL,
  `smtp_port` text DEFAULT NULL,
  `protocol` text DEFAULT NULL,
  `smtp_auth` text DEFAULT NULL,
  `smtp_socket` text DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_mail`
--

INSERT INTO `pinky_mail` (`id`, `smtp_host`, `smtp_username`, `smtp_password`, `smtp_port`, `protocol`, `smtp_auth`, `smtp_socket`) VALUES
(1, '', '', '', '', '1', 'true', 'ssl');

-- --------------------------------------------------------

--
-- Table structure for table `pinky_mail_templates`
--

CREATE TABLE `pinky_mail_templates` (
  `id` int(11) NOT NULL,
  `subject` text NOT NULL,
  `body` blob NOT NULL,
  `code` text NOT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_mail_templates`
--

INSERT INTO `pinky_mail_templates` (`id`, `subject`, `body`, `code`) VALUES
(1, 'e1NpdGVOYW1lfSAtIFBhc3N3b3JkIGNoYW5nZWQgc3VjY2Vzc2Z1bGx5', 0x4a6d78304f33416d5a33513753475673624738734a6d78304f324a794943386d5a33513744516f6d62485137596e49674c795a6e6444734e436c4a6c593256756447783549486c766458496759574e6a623356756443427759584e7a643239795a43426f59584d67596d566c626942795a584e6c64434269655342356233567949484a6c6358566c63335175494642735a57467a5a5342305957746c4947356c6479427759584e7a643239795a43423062794273623264706269346d62485137596e49674c795a6e6444734e43695a7364447469636941764a6d64304f77304b575739316369424f5a5863675547467a63336476636d51364948744f5a58645159584e7a643239795a48306d62485137596e49674c795a6e6444734e43695a7364447469636941764a6d64304f77304b5757393149474e68626942736232636761573467596e6b6764584e70626d6367655739316369423163325679626d46745a534268626d5167626d56334948426863334e3362334a6b49474a3549485a7063326c306157356e49473931636942335a574a7a6158526c4c695a7364447469636941764a6d64304f77304b4a6d78304f324a794943386d5a335137445170556147467561794235623355734a6d78304f324a794943386d5a33513744516f744946526f5a53423755326c305a553568625756394946526c5957306d624851374c33416d5a335137, 'password_reset');

-- --------------------------------------------------------

--
-- Table structure for table `pinky_notifications`
--

CREATE TABLE `pinky_notifications` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `path` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `added_by` text DEFAULT NULL,
  `date` text DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_notifications`
--

INSERT INTO `pinky_notifications` (`id`, `name`, `path`, `status`, `added_by`, `date`) VALUES
(1, 'Received', 'uploads/received.mp3', 1, '1', '12th June 2018');

-- --------------------------------------------------------

--
-- Table structure for table `pinky_plugins`
--

CREATE TABLE `pinky_plugins` (
  `id` int(11) NOT NULL,
  `execution_type` text DEFAULT NULL,
  `privilege` text DEFAULT NULL,
  `plugin_active` text DEFAULT NULL,
  `plugin_con_name` text DEFAULT NULL,
  `con_name` text DEFAULT NULL,
  `plugin_info` text DEFAULT NULL
)  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pinky_rainbowphp`
--

CREATE TABLE `pinky_rainbowphp` (
  `id` int(11) NOT NULL,
  `task` text DEFAULT NULL,
  `data` text DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_rainbowphp`
--

INSERT INTO `pinky_rainbowphp` (`id`, `task`, `data`) VALUES
(1, 'adblock', '{\\\"options\\\":\\\"close\\\",\\\"link\\\":\\\"{{baseLink}}test\\\",\\\"close\\\":{\\\"title\\\":\\\"Adblock detected!\\\",\\\"msg\\\":\\\"&lt;div class=&quot;text-center&quot;&gt;\\\\\\\\r\\\\\\\\n&lt;br&gt;\\\\\\\\r\\\\\\\\n&lt;i style=&quot;color: #e74c3c; font-size: 120px;&quot; class=&quot;fa fa-frown-o&quot; aria-hidden=&quot;true&quot;&gt;&lt;\\\\/i&gt;\\\\\\\\r\\\\\\\\n&lt;p class=&quot;bold&quot;&gt;We have detected that you are using adblocking plugin in your browser.&lt;\\\\/p&gt;\\\\\\\\r\\\\\\\\n\\\\\\\\r\\\\\\\\n&lt;p  class=&quot;bold&quot;&gt;\\\\\\\\r\\\\\\\\nThe revenue we earn by the advertisements is used to manage this website, we request you to whitelist our website in your adblocking plugin.&lt;\\\\/p&gt;\\\\\\\\r\\\\\\\\n&lt;p&gt;&lt;button onclick=&quot;location.reload();&quot; class=&quot;btn btn-success&quot;&gt;Refresh this Page&lt;\\\\/button&gt;&lt;\\\\/p&gt;\\\\\\\\r\\\\\\\\n&lt;br&gt;\\\\\\\\r\\\\\\\\n&lt;\\\\/div&gt;\\\"},\\\"force\\\":{\\\"title\\\":\\\"Adblock detected!\\\",\\\"msg\\\":\\\"&lt;div class=&quot;text-center&quot;&gt;\\\\\\\\r\\\\\\\\n&lt;br&gt;\\\\\\\\r\\\\\\\\n&lt;i style=&quot;color: #e74c3c; font-size: 120px;&quot; class=&quot;fa fa-frown-o&quot; aria-hidden=&quot;true&quot;&gt;&lt;\\\\/i&gt;\\\\\\\\r\\\\\\\\n&lt;p class=&quot;bold&quot;&gt;We have detected that you are using adblocking plugin in your browser.&lt;\\\\/p&gt;\\\\\\\\r\\\\\\\\n\\\\\\\\r\\\\\\\\n&lt;p  class=&quot;bold&quot;&gt;\\\\\\\\r\\\\\\\\nThe revenue we earn by the advertisements is used to manage this website, we request you to whitelist our website in your adblocking plugin.&lt;\\\\/p&gt;\\\\\\\\r\\\\\\\\n&lt;p&gt;&lt;button onclick=&quot;location.reload();&quot; class=&quot;btn btn-success&quot;&gt;Refresh this Page&lt;\\\\/button&gt;&lt;\\\\/p&gt;\\\\\\\\r\\\\\\\\n&lt;br&gt;\\\\\\\\r\\\\\\\\n&lt;\\\\/div&gt;\\\"},\\\"enable\\\":\\\"off\\\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pinky_rainbow_analytics`
--

CREATE TABLE `pinky_rainbow_analytics` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `ip` varchar(250) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ses_id` varchar(250) DEFAULT NULL,
  `pageviews` int(11) DEFAULT NULL,
  `pages` blob DEFAULT NULL,
  `ref` text DEFAULT NULL,
  `last_visit_raw` varchar(250) DEFAULT NULL,
  `last_visit` datetime DEFAULT NULL,
  `ua` text DEFAULT NULL,
  `screen` varchar(250) DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `user_path` blob DEFAULT NULL,
  `is_bot` int(11) DEFAULT NULL,
  `chatID` int(11) DEFAULT NULL
)  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pinky_site_info`
--

CREATE TABLE `pinky_site_info` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `doForce` text DEFAULT NULL,
  `copyright` text DEFAULT NULL,
  `other_settings` text DEFAULT NULL,
  `app_name` mediumtext DEFAULT NULL,
  `html_app` mediumtext DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_site_info`
--

INSERT INTO `pinky_site_info` (`id`, `site_name`, `email`, `doForce`, `copyright`, `other_settings`, `app_name`, `html_app`) VALUES
(1, 'Pinky Live Chat', 'demo@prothemes.biz', '[\\\"\\\",\\\"\\\"]', '&lt;strong&gt;Copyright &amp;copy; {{year}} &lt;a target=&quot;_blank&quot; href=&quot;https://prothemes.biz&quot;&gt;ProThemes.Biz&lt;/a&gt;&lt;/strong&gt; All rights reserved.', '{\\\"other\\\":{\\\"maintenance\\\":\\\"\\\",\\\"maintenance_mes\\\":\\\"We expect to be back within the hour.&lt;br\\\\/&gt;Sorry for the inconvenience.\\\",\\\"dbbackup\\\":{\\\"cronopt\\\":\\\"daily\\\"}}}', 'Pinky Chat', '&lt;span class=&quot;livechat icon-bubbles3&quot;&gt;&lt;/span&gt; Pinky &lt;b&gt;Chat&lt;/b&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `pinky_themes_data`
--

CREATE TABLE `pinky_themes_data` (
  `id` int(11) NOT NULL,
  `default_theme` mediumblob DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pinky_themes_data`
--

INSERT INTO `pinky_themes_data` (`id`, `default_theme`) VALUES
(1, 0x7b5c22636861745c223a7b5c226261636b67726f756e645c223a5c22236666666566625c222c5c2273697a655c223a5c2231325c222c5c226c6162656c5c223a5c22236666666666665c222c5c22626f726465725c223a5c22236666376661615c222c5c227573697a655c223a5c2231305c222c5c226772616469656e745c223a5c22315c222c5c2268636f6c6f72315c223a5c22236666363738645c222c5c2268636f6c6f72325c223a5c22236666376661615c222c5c226873697a655c223a5c2231335c227d2c5c227042746e5c223a7b5c226772616469656e745c223a5c22315c222c5c2268636f6c6f72315c223a5c22236666363738645c222c5c2268636f6c6f72325c223a5c22236666376661615c222c5c22626f726465725c223a5c22236666376661615c222c5c22636f6c6f725c223a5c22236666666666665c227d2c5c227042746e685c223a7b5c226772616469656e745c223a5c22315c222c5c2268636f6c6f72315c223a5c22236666373439385c222c5c2268636f6c6f72325c223a5c22236666386262325c222c5c22626f726465725c223a5c22236666386262325c222c5c22636f6c6f725c223a5c22236666666666665c227d2c5c226242746e5c223a7b5c226261636b5c223a5c22302c3231372c3234305c222c5c22626f726465725c223a5c22302c3231372c3234305c222c5c22636f6c6f725c223a5c22236666666666665c227d2c5c226772617942746e5c223a7b5c226261636b5c223a5c223136352c3137362c3139345c222c5c22626f726465725c223a5c223136352c3137362c3139345c222c5c22636f6c6f725c223a5c22236666666666665c227d2c5c226742746e5c223a7b5c226261636b5c223a5c223137342c3231372c3131355c222c5c22626f726465725c223a5c223137342c3231372c3131355c222c5c22636f6c6f725c223a5c22236666666666665c227d2c5c226f42746e5c223a7b5c226261636b5c223a5c223235342c3136352c31335c222c5c22626f726465725c223a5c223235342c3136352c31335c222c5c22636f6c6f725c223a5c22236666666666665c227d2c5c227242746e5c223a7b5c226261636b5c223a5c223235322c39322c3130305c222c5c22626f726465725c223a5c223235322c39322c3130305c222c5c22636f6c6f725c223a5c22236666666666665c227d2c5c22637573746f6d5c223a7b5c226373735c223a5c225c227d2c5c2272657365745c223a7b5c22637573746f6d5c223a7b5c226373735c223a5c225c227d2c5c22636861745c223a7b5c226772616469656e745c223a5c22315c222c5c2268636f6c6f72315c223a5c22236666363738645c222c5c2268636f6c6f72325c223a5c22234646374641415c222c5c22626f726465725c223a5c22234646374641415c222c5c226261636b67726f756e645c223a5c22236666666566625c222c5c226c6162656c5c223a5c22236666666666665c222c5c2273697a655c223a5c2231325c222c5c227573697a655c223a5c2231305c222c5c226873697a655c223a5c2231335c227d2c5c227042746e5c223a7b5c226772616469656e745c223a5c22315c222c5c2268636f6c6f72315c223a5c22236666363738645c222c5c2268636f6c6f72325c223a5c22234646374641415c222c5c22626f726465725c223a5c22234646374641415c222c5c22636f6c6f725c223a5c22236666666666665c227d2c5c227042746e685c223a7b5c226772616469656e745c223a5c22315c222c5c2268636f6c6f72315c223a5c22234646373539385c222c5c2268636f6c6f72325c223a5c22234646384242325c222c5c22626f726465725c223a5c22234646384242325c222c5c22636f6c6f725c223a5c22236666666666665c227d2c5c226242746e5c223a7b5c226261636b5c223a5c22312c203231372c203234305c222c5c22626f726465725c223a5c22312c203231372c203234305c222c5c22636f6c6f725c223a5c22236666666666665c227d2c5c226742746e5c223a7b5c226261636b5c223a5c223137352c203231372c203131355c222c5c22626f726465725c223a5c223137352c203231372c203131355c222c5c22636f6c6f725c223a5c22236666666666665c227d2c5c227242746e5c223a7b5c226261636b5c223a5c223235322c2039322c203130315c222c5c22626f726465725c223a5c223235322c2039322c203130315c222c5c22636f6c6f725c223a5c22236666666666665c227d2c5c226f42746e5c223a7b5c226261636b5c223a5c223235342c203136362c2031335c222c5c22626f726465725c223a5c223235342c203136362c2031335c222c5c22636f6c6f725c223a5c22236666666666665c227d2c5c226772617942746e5c223a7b5c226261636b5c223a5c223136352c203137372c203139345c222c5c22626f726465725c223a5c223136352c203137372c203139345c222c5c22636f6c6f725c223a5c22236666666666665c227d7d7d);

-- --------------------------------------------------------

--
-- Table structure for table `pinky_users`
--

CREATE TABLE `pinky_users` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `created_ip` text DEFAULT NULL,
  `created_at` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `last_active` text DEFAULT NULL,
  `data` text DEFAULT NULL
)  DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pinky_admin`
--
ALTER TABLE `pinky_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_admin_chat_settings`
--
ALTER TABLE `pinky_admin_chat_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_admin_history`
--
ALTER TABLE `pinky_admin_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_admin_roles`
--
ALTER TABLE `pinky_admin_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_avatars`
--
ALTER TABLE `pinky_avatars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_banned_ip`
--
ALTER TABLE `pinky_banned_ip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_canned_msg`
--
ALTER TABLE `pinky_canned_msg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_capthca`
--
ALTER TABLE `pinky_capthca`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_chat`
--
ALTER TABLE `pinky_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_chat_history`
--
ALTER TABLE `pinky_chat_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_chat_settings`
--
ALTER TABLE `pinky_chat_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_chat_uploads`
--
ALTER TABLE `pinky_chat_uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_departments`
--
ALTER TABLE `pinky_departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_emoticon`
--
ALTER TABLE `pinky_emoticon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_emoticon_data`
--
ALTER TABLE `pinky_emoticon_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_guest_chat`
--
ALTER TABLE `pinky_guest_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_interface`
--
ALTER TABLE `pinky_interface`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_invite_chat`
--
ALTER TABLE `pinky_invite_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_lang`
--
ALTER TABLE `pinky_lang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_mail`
--
ALTER TABLE `pinky_mail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_mail_templates`
--
ALTER TABLE `pinky_mail_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_notifications`
--
ALTER TABLE `pinky_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_rainbowphp`
--
ALTER TABLE `pinky_rainbowphp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_rainbow_analytics`
--
ALTER TABLE `pinky_rainbow_analytics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_site_info`
--
ALTER TABLE `pinky_site_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_themes_data`
--
ALTER TABLE `pinky_themes_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinky_users`
--
ALTER TABLE `pinky_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pinky_admin`
--
ALTER TABLE `pinky_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pinky_admin_chat_settings`
--
ALTER TABLE `pinky_admin_chat_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pinky_admin_history`
--
ALTER TABLE `pinky_admin_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pinky_admin_roles`
--
ALTER TABLE `pinky_admin_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pinky_avatars`
--
ALTER TABLE `pinky_avatars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pinky_banned_ip`
--
ALTER TABLE `pinky_banned_ip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pinky_canned_msg`
--
ALTER TABLE `pinky_canned_msg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pinky_capthca`
--
ALTER TABLE `pinky_capthca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pinky_chat`
--
ALTER TABLE `pinky_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pinky_chat_history`
--
ALTER TABLE `pinky_chat_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pinky_chat_settings`
--
ALTER TABLE `pinky_chat_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pinky_chat_uploads`
--
ALTER TABLE `pinky_chat_uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pinky_departments`
--
ALTER TABLE `pinky_departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pinky_emoticon`
--
ALTER TABLE `pinky_emoticon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pinky_emoticon_data`
--
ALTER TABLE `pinky_emoticon_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `pinky_guest_chat`
--
ALTER TABLE `pinky_guest_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pinky_interface`
--
ALTER TABLE `pinky_interface`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pinky_invite_chat`
--
ALTER TABLE `pinky_invite_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pinky_lang`
--
ALTER TABLE `pinky_lang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=977;

--
-- AUTO_INCREMENT for table `pinky_mail`
--
ALTER TABLE `pinky_mail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pinky_mail_templates`
--
ALTER TABLE `pinky_mail_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pinky_notifications`
--
ALTER TABLE `pinky_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pinky_rainbowphp`
--
ALTER TABLE `pinky_rainbowphp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pinky_rainbow_analytics`
--
ALTER TABLE `pinky_rainbow_analytics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pinky_site_info`
--
ALTER TABLE `pinky_site_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pinky_themes_data`
--
ALTER TABLE `pinky_themes_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pinky_users`
--
ALTER TABLE `pinky_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
