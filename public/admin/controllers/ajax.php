<?php
defined('APP_NAME') or die(header('HTTP/1.1 403 Forbidden'));


//AJAX ONLY 

//Say Hello
if($pointOut === 'hello'){
    echo 'Hello';
    die();
}

//File Manager
if($pointOut === 'exploder')
    redirectTo(adminLink('exploder/index.php?login',true,true));

//Logout from Admin Panel
if($pointOut === 'logout'){
    if(isset($_SESSION[N_APP.'AdminToken'])) {
        updateToDbPrepared($con, 'admin', array('last_visit' => NULL), array('id' => getSession('AdminID')));
        clearSession(null, 'admin');
    }
    redirectTo($adminBaseURL);
}

//Theme Switcher
if($pointOut === 'theme'){
    if($args[1] == 'frontend'){
        $themeDirName = $args[2];
        if(setTheme($con,$themeDirName)){
            header('Location: '. adminLink('manage-themes/success',true));
        }else{
            header('Location: '. adminLink('manage-themes/failed',true));
        }
    }
    die();
}

//Upload Handler - Mail Attachments
if($pointOut === 'phpinfo'){
    phpinfo();
    die();
}

//Upload Handler - Mail Attachments
if($pointOut == 'mail-upload'){
    $upload_handler = new uploadhandler();
    die();
}

//Visitors Log
if($pointOut === 'visitors-range'){

$startDate = $args[0];
$endDate = $args[1];
$geoData = loadIp2();
$flagPath = ROOT_DIR.'resources'.D_S.'flags'.D_S.'default'.D_S.'24'.D_S;
$iconPath = ROOT_DIR.'resources'.D_S.'icons'.D_S;
$flagLink = $baseURL.'resources/flags/default/24/';
$iconLink = $baseURL.'resources/icons/';
$screenLink = $iconLink.'screen.png';
$rainbowTrackBalaji = '';

if($startDate == $endDate){
$date = $startDate;
$datas = array_reverse(getTrackRecords($date,$con));
foreach($datas as $data){

    $userCountryCode = ip2Code($geoData, $data['ip']);

    $userCountry = country_code_to_country($userCountryCode);
    $userCountry = ($userCountry == '') ? $lang['CH75'] : $userCountry;

    if(file_exists($flagPath.strtolower(Trim($userCountry)).'.png'))
        $coLink = $flagLink.strtolower(Trim($userCountry)).'.png';
    else
        $coLink = $flagLink.'unknown.png';
    $uaInfo = parse_user_agent($data['ua']);
    if(file_exists($iconPath.strtolower($uaInfo['platform']).'.png'))
        $osLink = $iconLink.strtolower($uaInfo['platform']).'.png';
    else
        $osLink = $iconLink.'unknown.png';
    if(file_exists($iconPath.strtolower($uaInfo['browser']).'.png'))
        $browserLink = $iconLink.strtolower($uaInfo['browser']).'.png';
    else
        $browserLink = $iconLink.'unknown.png';

    $pageData = '';
    foreach($data['pages'] as $pageV){
        $pageData .= '<div class="pagesWell"><a target="_blank" href="'.$pageV[0].'">'.$pageV[0].'</a><br>
        '.$lang['CH650'].': '.$pageV[1].' <br>
        '.$lang['CH651'].': '.date('h:i:s A',$pageV[2]).'</div>
        ';
    }

    if($data['ref'] !== 'Direct')
        $data['ref'] = '<a href="'.$data['ref'].'" target="_blank">'.getDomainName($data['ref']).'</a>';

    $data['user_id]'] = intval($data['user_id]']);
    if($data['user_id]'] === 0)
        $username = 'Guest';
    else
        $username = ucfirst(getUserName($con, $data['user_id']));

    $rainbowTrackBalaji .= '
    <tr>
        <td>
        <img src="'.$coLink.'" alt="'.$userCountryCode.'" />  <strong class="b16">'.ucfirst($userCountry).'</strong><br><br>
        <strong>'.date('F jS Y h:i:s A',strtotime($data['created'])).'</strong> <br>
        '.$lang['RF66'].': '.$username.'<br>
        '.$lang['CH652'].': '.$data['pageviews'].'<br>
        '.$lang['CH92'].': <span class="badge" style="background-color: '.rndFlatColor().' !important;">'.$data['ip'].'</span><br><br>
        '.$lang['CH653'].': '.$data['ref'].'<br>
        </td>
        <td><img data-toggle="tooltip" data-placement="top" title="'.$lang['CH640'].': '.$uaInfo['platform'].'" src="'.$osLink.'" alt="'.$uaInfo['platform'].'" />
        <img data-toggle="tooltip" data-placement="top" title="'.$lang['CH90'].': '.$uaInfo['browser'].' '.$uaInfo['version'].'" src="'.$browserLink.'" alt="'.$uaInfo['browser'].'" />
        <img data-toggle="tooltip" data-placement="top" title="'.$lang['CH654'].': '.$data['screen'].'" src="'.$screenLink.'" />
        </td>
        <td>'.$pageData.'</td>
    </tr>
    
    ';
}

}else{
$diff = 0;
$datetime1 = date_create($startDate);
$datetime2 = date_create($endDate);
$interval = date_diff($datetime1, $datetime2);
$diff =  $interval->format('%a');
if($diff >= 366){
    $rainbowTrackBalaji = '<tr><td style="color: red;"><b>'.$lang['CH756'].'</b></td><td style="display: none;"></td><td style="display: none;"></td></tr>';
}else{
$masterDatas = array_reverse(getTrackRecordsRange($startDate,$endDate,$con));
foreach($masterDatas as $datas){
foreach($datas as $data){

    $userCountryCode = ip2Code($geoData, $data['ip']);

    $userCountry = country_code_to_country($userCountryCode);
    $userCountry = ($userCountry == '') ? 'Unknown' : $userCountry;
    if(file_exists($flagPath.strtolower(Trim($userCountry)).'.png'))
        $coLink = $flagLink.strtolower(Trim($userCountry)).'.png';
    else
        $coLink = $flagLink.'unknown.png';
    $uaInfo = parse_user_agent($data['ua']);
    if(file_exists($iconPath.strtolower($uaInfo['platform']).'.png'))
        $osLink = $iconLink.strtolower($uaInfo['platform']).'.png';
    else
        $osLink = $iconLink.'unknown.png';
    if(file_exists($iconPath.strtolower($uaInfo['browser']).'.png'))
        $browserLink = $iconLink.strtolower($uaInfo['browser']).'.png';
    else
        $browserLink = $iconLink.'unknown.png';

    $pageData = '';
    foreach($data['pages'] as $pageV){
        $pageData .= '<div class="pagesWell"><a target="_blank" href="'.$pageV[0].'">'.$pageV[0].'</a><br>
        '.$lang['CH650'].': '.$pageV[1].' <br>
        '.$lang['CH651'].': '.date('h:i:s A',$pageV[2]).'</div>
        ';
    }

    if($data['ref'] !== 'Direct')
        $data['ref'] = '<a href="'.$data['ref'].'" target="_blank">'.getDomainName($data['ref']).'</a>';

    $data['user_id]'] = intval($data['user_id]']);
    if($data['user_id]'] === 0)
        $username = 'Guest';
    else
        $username = ucfirst(getUserName($con, $data['user_id']));

    $rainbowTrackBalaji .= '
    <tr>
        <td>
        <img src="'.$coLink.'" alt="'.$userCountryCode.'" />  <strong class="b16">'.ucfirst($userCountry).'</strong><br><br>
        <strong>'.date('F jS Y h:i:s A',strtotime($data['created'])).'</strong> <br>
        '.$lang['RF66'].': '.$username.'<br>
        '.$lang['CH652'].': '.$data['pageviews'].'<br>
        '.$lang['CH92'].': <span class="badge" style="background-color: '.rndFlatColor().' !important;">'.$data['ip'].'</span><br><br>
        '.$lang['CH653'].': '.$data['ref'].'<br>
        </td>
        <td><img data-toggle="tooltip" data-placement="top" title="'.$lang['CH640'].': '.$uaInfo['platform'].'" src="'.$osLink.'" alt="'.$uaInfo['platform'].'" />
        <img data-toggle="tooltip" data-placement="top" title="'.$lang['CH90'].': '.$uaInfo['browser'].' '.$uaInfo['version'].'" src="'.$browserLink.'" alt="'.$uaInfo['browser'].'" />
        <img data-toggle="tooltip" data-placement="top" title="'.$lang['CH654'].': '.$data['screen'].'" src="'.$screenLink.'" />
        </td>
        <td>'.$pageData.'</td>
    </tr>
    
    ';
}
    
}

}
}

echo $rainbowTrackBalaji;
die();
}

//Get all customers informations
if($pointOut === 'manageUsers'){
    //DB table to use
    $table = DB_PREFIX.'users';
    
    //Table's primary key
    $primaryKey = 'id';
    
    //Database columns
    $columns = array(
    	array( 'db' => 'name', 'dt' => 0 ),
    	array( 'db' => 'email', 'dt' => 1 ),
    	array( 'db' => 'created_ip',  'dt' => 2 ),
    	array( 'db' => 'created_at',  'dt' => 3),
    	array( 'db' => 'last_active',   'dt' => 4 ),
    	array( 'db' => 'id',   'dt' => 5 )
    );
    
    $columns2 = array(
    	array( 'db' => 'name', 'dt' => 0 ),
    	array( 'db' => 'email', 'dt' => 1 ),
    	array( 'db' => 'created_ip',  'dt' => 2 ),
    	array( 'db' => 'country',  'dt' => 3),
    	array( 'db' => 'created_at',   'dt' => 4 ),
    	array( 'db' => 'last_active',   'dt' => 5 ),
    	array( 'db' => 'actions',   'dt' => 6)
    );

    //Addtional Variables
    $other = array(
        'action' => 'users',
        'lang' => $lang
    );
    
    // SQL connection information
    $sql_details = array(
    	'user' => DB_USER,
    	'pass' => DB_PASS,
    	'db'   => DB_NAME,
    	'host' => DB_HOST
    );
    
    echo json_encode(
    	SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $columns2, $other)
    );
    die();   
}

//Get all admin informations
if($pointOut === 'manageAdmins'){
    //DB table to use
    $table = DB_PREFIX.'admin';

    //Table's primary key
    $primaryKey = 'id';

    //Database columns
    $columns = array(
        array( 'db' => 'name', 'dt' => 0),
        array( 'db' => 'user', 'dt' => 1),
        array( 'db' => 'reg_date',  'dt' => 2),
        array( 'db' => 'role',  'dt' => 3),
        array( 'db' => 'id',  'dt' => 4),
    );

    $columns2 = array(
        array( 'db' => 'checkbox', 'dt' => 'DT_RowId',
            'formatter' => function($d) {
                return 'myid_'.$d;
            }
        ),
        array( 'db' => 'name', 'dt' => 1 ),
        array( 'db' => 'user', 'dt' => 2 ),
        array( 'db' => 'reg_date',  'dt' => 3),
        array( 'db' => 'role',  'dt' => 4),
        array( 'db' => 'access',  'dt' => 5),
        array( 'db' => 'actions',   'dt' => 6),
        array( 'db' => '_id',   'dt' => 'id'),
    );

    //Addtional Variables
    $other = array(
        'action' => 'admin',
        'con' => $con,
        'lang' => $lang
    );

    // SQL connection information
    $sql_details = array(
        'user' => DB_USER,
        'pass' => DB_PASS,
        'db'   => DB_NAME,
        'host' => DB_HOST
    );

    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $columns2, $other)
    );
    die();
}

//Get Chat History
if($pointOut === 'chatHistory'){
    //DB table to use
    $table = DB_PREFIX.'chat';

    //Table's primary key
    $primaryKey = 'id';

    //Database columns
    $columns = array(
        array( 'db' => 'date',   'dt' => 0 ),
        array( 'db' => 'user_id',  'dt' => 1 ),
        array( 'db' => 'status',  'dt' => 2),
        array( 'db' => 'dept', 'dt' => 3 ),
        array( 'db' => 'admin',   'dt' => 4 ),
        array( 'db' => 'rate',   'dt' => 5 ),
        array( 'db' => 'id',   'dt' => 6 )
    );

    $columns2 = array(
        array( 'db' => 'checkbox', 'dt' => 'DT_RowId',
            'formatter' => function($d) {
                return 'myid_'.$d;
            }
        ),
        array( 'db' => 'name',  'dt' => 1 ),
        array( 'db' => 'email',  'dt' => 2 ),
        array( 'db' => 'date',  'dt' => 3),
        array( 'db' => 'status',  'dt' => 4),
        array( 'db' => 'department', 'dt' => 5),
        array( 'db' => 'rate', 'dt' => 6),
        array( 'db' => 'actions',   'dt' => 7 ),
        array( 'db' => '_id',   'dt' => 'id'),
        array( 'db' => '_disabled',   'dt' => 'disabled'),
    );

    //Addtional Variables
    $other = array(
        'action' => 'history',
        'con' => $con,
        'lang' => $lang
    );

    //SQL connection information
    $sql_details = array(
        'user' => DB_USER,
        'pass' => DB_PASS,
        'db'   => DB_NAME,
        'host' => DB_HOST
    );

    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $columns2, $other)
    );
    die();
}


//Get Canned Messages
if($pointOut === 'cannedMsg'){
    //DB table to use
    $table = DB_PREFIX.'canned_msg';

    //Table's primary key
    $primaryKey = 'id';

    //Database columns
    $columns = array(
        array( 'db' => 'code',   'dt' => 0 ),
        array( 'db' => 'data',  'dt' => 1 ),
        array( 'db' => 'date',  'dt' => 2),
        array( 'db' => 'admin', 'dt' => 3 ),
        array( 'db' => 'status',   'dt' => 4 ),
        array( 'db' => 'id',   'dt' => 5 )
    );

    $columns2 = array(
        array( 'db' => 'checkbox', 'dt' => 'DT_RowId',
            'formatter' => function($d) {
                return 'myid_'.$d;
            }
        ),
        array( 'db' => 'code',  'dt' => 1 ),
        array( 'db' => 'data',  'dt' => 2 ),
        array( 'db' => 'date',  'dt' => 3),
        array( 'db' => 'admin',  'dt' => 4),
        array( 'db' => 'status', 'dt' => 5),
        array( 'db' => 'actions',   'dt' => 6 ),
        array( 'db' => '_id',   'dt' => 'id'),
        array( 'db' => '_disabled',   'dt' => 'disabled'),
    );

    //Addtional Variables
    $other = array(
        'action' => 'canned',
        'con' => $con,
        'lang' => $lang
    );

    //SQL connection information
    $sql_details = array(
        'user' => DB_USER,
        'pass' => DB_PASS,
        'db'   => DB_NAME,
        'host' => DB_HOST
    );

    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $columns2, $other)
    );
    die();
}

//Get departments
if($pointOut === 'departments'){
    //DB table to use
    $table = DB_PREFIX.'departments';

    //Table's primary key
    $primaryKey = 'id';

    //Database columns
    $columns = array(
        array( 'db' => 'name',   'dt' => 0 ),
        array( 'db' => 'des',  'dt' => 1 ),
        array( 'db' => 'data',  'dt' => 2),
        array( 'db' => 'status', 'dt' => 3 ),
        array( 'db' => 'id',   'dt' => 4 )
    );

    $columns2 = array(
        array( 'db' => 'checkbox', 'dt' => 'DT_RowId',
            'formatter' => function($d) {
                return 'myid_'.$d;
            }
        ),
        array( 'db' => 'name',  'dt' => 1 ),
        array( 'db' => 'des',  'dt' => 2 ),
        array( 'db' => 'data',  'dt' => 3),
        array( 'db' => 'status',  'dt' => 4),
        array( 'db' => 'actions',   'dt' => 5),
        array( 'db' => '_id',   'dt' => 'id'),
        array( 'db' => '_disabled',   'dt' => 'disabled'),
    );

    //Addtional Variables
    $other = array(
        'action' => 'departments',
        'con' => $con,
        'lang' => $lang
    );

    //SQL connection information
    $sql_details = array(
        'user' => DB_USER,
        'pass' => DB_PASS,
        'db'   => DB_NAME,
        'host' => DB_HOST
    );

    echo json_encode(
        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $columns2, $other)
    );
    die();
}

if($pointOut === 'getCustomers'){
    $data = array();
    $term = escapeTrim($con,$_GET['term']);
    $qstring = "SELECT name,id,email,created_at FROM ".DB_PREFIX."users WHERE name LIKE '%".$term."%'";
    $result = mysqli_query($con,$qstring);

    while ($row = mysqli_fetch_assoc($result)){
        $data[] = $row['name'].'|'.$row['email'].'|'.$row['created_at'].'|'.$row['id'];
    }
    echo json_encode($data);
    die();
}


die();