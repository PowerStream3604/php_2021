<?php
include_once('./_common.php');

/*==========================
$w == a : 답변
$w == r : 추가질문
$w == u : 수정
==========================*/
/* alert('이름을 입력해주세요'); */



if($is_guest)
    alert('회원이시라면 로그인 후 이용해 보십시오.', './login.php?url='.urlencode(G5_BBS_URL.'/qalist.php'));
//echo $_REQUEST['qa_category']."weg";



// 이름( consultName ) validation
$consultName = $_REQUEST['qa_name'];
$consultName = preg_replace("#[\\\]+$#", "", $consultName);
if(!$consultName ){// if(!isset($consultName))을 이용하면 왜 안되는지
	alert("이름을 입력하세요");
}

//전화번호 validation
$phoneNumber = $_REQUEST['qa_tele_num'];
$phoneNumber = preg_replace("#[\\\]+$#", "", $phoneNumber);
if(!$phoneNumber){
	alert("전화번호를 입력하세요");
}

$addr = $_REQUEST['qa_addr'];//주소
$addr = preg_replace("#[\\\]+$#", "", $addr);

$loanAmount = $_REQUEST['qa_loan_amount'];//희망대출금액
$loanAmount = preg_replace("#[\\\]+$#", "", $loanAmount);

$businessManage = $_REQUEST['qa_business_manage'];//영업방법
$businessManage = preg_replace("#[\\\]+$#", "", $businessManage);

$adminDivision = $_REQUEST['qa_admin_distin'];//관리자구분
$adminDivision = preg_replace("#[\\\]+$#", "", $adminDivision);

$counselor = $_REQUEST['qa_counselor'];//상담사
$counselor = preg_replace("#[\\\]+$#", "", $counselor);

$counselContent = $_REQUEST['qa_counsel_content'];//상담내용

$receiveType = $_REQUEST['qa_receive_type'];// 접수방법
$receiveType = preg_replace("#[\\\]+$#", "", $receiveType);

$thirdPartyAgree = $_REQUEST['qa_third_confirm'];//제3자 정보제공동의 필요
if($thirdPartyAgree == "agree"){
	//동의
	// 데이터 베이스에서 1은 동의 , 2는 비동의 
	$thirdPartyNum = 1; // 동의
}else {
	$thirdPartyNum = 2; // 비동의
}


$registerDate = $_REQUEST['qa_reg_date'];//신청날짜
if( (!$registerDate == "")){//필수적으로 입력해야하는 값을 아니기 때문에 입력값이 있을때문 validation 을 하면 됩니다.
if( !preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $registerDate) ){// YYYY-MM-DD형식이 맞는지 validation하는 부분
    alert("신청날짜가 YYYY-MM-DD형식에 맞지 않습니다.");
}
}

$urlReference = $_REQUEST['qa_url_refer'];//URL 참조
$adminId = $_REQUEST['admin_id'];//어드민 아이디
$masterId = $_REQUEST['master_id'];//마스터아이디
$sectionId = $_REQUEST['section_id'];//부서아이디

$loanType = $_REQUEST['qa_loan_type'];//대출종류 (select menu)
if (!ctype_digit($loanType)){// select menu를 통해서 넘어온 값이 숫자인지 확인하는 작업
	alert("유효하지 않은 대출종류입니다.");
}

$region = $_REQUEST['qa_region'];//지역 (select menu)
if (!ctype_digit($region)){// select menu를 통해서 넘어온 값이 숫자인지 확인하는 작업
	alert("유효하지 않은 지역입니다.");
}



$progress = $_REQUEST['qa_progress'];//진행상태 (select menu)

if (!ctype_digit($progress)){// select menu를 통해서 넘어온 값이 숫자인지 확인하는 작업
	alert("유효하지 않은 진행상태입니다.");
}

$noticeDate = $_REQUEST['qa_no_date'];//알림날짜
if(!($noticeDate =="")){//필수적으로 입력해야 하는것은 아니기 때문에 입력값이 있을때만 validation을 하면 된다.
if( !preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $noticeDate) ){// YYYY-MM-DD형식이 맞는지 validation하는 부분
    alert("알림날짜가 YYYY-MM-DD형식에 맞지 않습니다.");
}
}

// 접속 URl관련 부분을 해야합니다. (접속 URL은 입력받는 부분이 아니라 이전에 넘어온 referer의 값을 확인해서 자동으로 입력하는 것입니다.)
$accessUrl = $_REQUEST['qa_access_url'];

// 현재 참조 URL



//Creating a connection
/*
function GetDbConnection() {
	$dbConn = mysqli_connect($dbServerDomain, $dbUser, $dbPassword, $dbName);
	// DB 연결이 잘 되었는지 확인
	if(mysqli_connect_errno()){
		alert("DB 연결에 실패 했습니다.");// 아래의 exit문이 꼭 필요한지 alert()만 호출되면 그냥 뒤로 넘어가서 실행 흐름이 거기로 가는지
		exit;
	}
	echo "DB연결에 성공";
	return $dbConn;
}
GetDbConnection();
*/

//createdAt, updatedAt에 현재 서버의 시간을 할당하는 변수
$currentTime = date("Y-m-d H:i:s");

// DB에 상담한 항목들을 추가하는 작업
$consultSql = "INSERT INTO g5_consult
set Name='{$consultName}',
	Adr='{$addr}',
	reqLoaAmoun='{$loanAmount}',
	busiMana='{$businessManage}' ,
	adminDis='{$adminDivision}' ,
	counse='{$counselor}' ,
	counCon='{$counselContent}' ,
	recT='{$receiveType}' ,
	pNum='{$phoneNumber}' ,
	thConf='{$thirdPartyNum}' ,
	regDa='{$registerDate}' ,
	admId='{$adminId}' ,
	masId='{$masterId}' ,
	secId='{$sectionId}' ,
	loTyp='{$loanType}',
	regi='{$region}',
	prog='{$progress}',
	referUrl='{$urlReference}',
	acUrl='{$accessUrl}',
	nDate='{$noticeDate}',
	createdAt='{$currentTime}',
	updatedAt='{$currentTime}'
";
sql_query($consultSql);

$consultFileCId = sql_insert_id();


$notAllowedExts = array("js","html","php");

// 첨부파일 메뉴

if(isset($_FILES['qa_file']) && $_FILES['qa_file']['name'] != ""){
	echo "***************************"."파일 업로드함";
	$attachedFile = $_FILES['qa_file'];//첨부파일 메뉴
	$file = $_FILES["file"];
    $fileError = $file["error"];
	//originalFileName column 에 저장될 진짜 파일의 이름
    $originalFileName = $file["name"];
    $fileType = $file["type"];
    $fileSize = $file["size"];
	
	// filename column에 저장될 항목이다 md5(microtime())+3자리 랜덤한 숫자값
	$savedFileName = "".md5(microtime()).rand(000,999);

	$maxFileSize = 20971520; //파일 업로드 최대 크기 20MB
	echo print_r($attachedFile);
	
	//$_FILES['qa_file']에서    name = 파일이름, type = 파일형식(jpg 이미지의 경우 image/jpeg), tmp_name = /tmp/phpkxlpcA, size = 의 경우 파일의 크기(byte)로
	// tmp_name = /tmp/phpkxlpcA/
	// /tmp/phpkxlpcA/data에 파일 업로드 시키면 됨
	
	if ($fileError > 0){
		alert("파일 첨부중 에러가 발생했습니다.");
	}
	else{ // 에러가 발생하지 않을 경우 파일 업로드 처리를 진행한다
		temp = explode(".",$savedFileName);
		$fileExtension = end($temp);
		
		// 파일 유효성 검사(최대 크기 20MB 미만), 확장자중 (js, html, php는 악성스크립트로 간주하고 업로드 거부)
		if(($size < $maxFileSize) && !(in_array($fileExtension, $notAllowedExts))){	
														//************여기 변수로 수정이 필요하다
			if(move_upload_file($file['tmp_name'], "data/".$savedFileName){
				//////******* 위의 g5에 넣을 때 연결하고나서 그대로 connection을 사용하는 쪽으로 해야함 $dbConn = GetDbConnection();
				//$query = "INSERT INTO g5_consult_file(cId,fileName,originalFileName,fileLocation,registerDate,createdAt,updatedAt) VALUES (?,?,?,?,now(),now(),now());";

				$consultFileQuery = "INSERT INTO g5_consult_file set cId='{$consultFileCId}', fileName='{$savedFileName}',originalFileName='{$originalFileName}',registerDate='{$currentTime}',createdAt='{$currentTime}',updatedAt='{$currentTime}';";

				sql_query($consultFileQuery);

			}
				
		}else{alert("파일의 크기가 20MB이상이거나 확장자가 앎맞지 않습니다.");}
		
	}
	
	
}


/*
$msg = array();

// 1:1문의 설정값
$qaconfig = get_qa_config();
$qa_id = isset($qa_id) ? (int) $qa_id : 0;

// e-mail 체크

$qa_email = '';
if(isset($_POST['qa_email']) && $_POST['qa_email'])
    $qa_email = get_email_address(trim($_POST['qa_email']));

if($w != 'a' && $qaconfig['qa_req_email'] && !$qa_email)
    $msg[] = '이메일을 입력하세요.';

$qa_subject = '';
if (isset($_POST['qa_subject'])) {
    $qa_subject = substr(trim($_POST['qa_subject']),0,255);
    $qa_subject = preg_replace("#[\\\]+$#", "", $qa_subject);
}
if ($qa_subject == '') {
    $msg[] = '<strong>제목</strong>을 입력하세요.';
}

$qa_content = '';
if (isset($_POST['qa_content'])) {
    $qa_content = substr(trim($_POST['qa_content']),0,65536);
    $qa_content = preg_replace("#[\\\]+$#", "", $qa_content);
}
if ($qa_content == '') {
    $msg[] = '<strong>내용</strong>을 입력하세요.';
}

if (!empty($msg)) {
    $msg = implode('<br>', $msg);
    alert($msg);
}

if($qa_hp)
    $qa_hp = preg_replace('/[^0-9\-]/', '', strip_tags($qa_hp));

// 090710
if (substr_count($qa_content, '&#') > 50) {
    alert('내용에 올바르지 않은 코드가 다수 포함되어 있습니다.');
    exit;
}

$upload_max_filesize = ini_get('upload_max_filesize');

if (empty($_POST)) {
    alert("파일 또는 글내용의 크기가 서버에서 설정한 값을 넘어 오류가 발생하였습니다.\\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=".$upload_max_filesize."\\n게시판관리자 또는 서버관리자에게 문의 바랍니다.");
}

for ($i=1; $i<=5; $i++) {
    $var = "qa_$i";
    $$var = "";
    if (isset($_POST['qa_'.$i]) && $_POST['qa_'.$i]) {
        $$var = trim($_POST['qa_'.$i]);
    }
}

if($w == 'u' || $w == 'a' || $w == 'r') {
    if($w == 'a' && !$is_admin)
        alert('답변은 관리자만 등록할 수 있습니다.');

    $sql = " select * from {$g5['qa_content_table']} where qa_id = '$qa_id' ";
    if(!$is_admin) {
        $sql .= " and mb_id = '{$member['mb_id']}' ";
    }

    $write = sql_fetch($sql);

    if($w == 'u') {
        if(!$write['qa_id'])
            alert('게시글이 존재하지 않습니다.\\n삭제되었거나 자신의 글이 아닌 경우입니다.');

        if(!$is_admin) {
            if($write['qa_type'] == 0 && $write['qa_status'] == 1)
                alert('답변이 등록된 문의글은 수정할 수 없습니다.');

            if($write['mb_id'] != $member['mb_id'])
                alert('게시글을 수정할 권한이 없습니다.\\n\\n올바른 방법으로 이용해 주십시오.', G5_URL);
        }
    }

    if($w == 'a') {
        if(!$write['qa_id'])
            alert('문의글이 존재하지 않아 답변글을 등록할 수 없습니다.');

        if($write['qa_type'] == 1)
            alert('답변글에는 다시 답변을 등록할 수 없습니다.');
    }
}

// 파일개수 체크
$file_count   = 0;
$upload_count = count($_FILES['bf_file']['name']);

for ($i=1; $i<=$upload_count; $i++) {
    if($_FILES['bf_file']['name'][$i] && is_uploaded_file($_FILES['bf_file']['tmp_name'][$i]))
        $file_count++;
}

if($file_count > 2)
    alert('첨부파일을 2개 이하로 업로드 해주십시오.');

// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
@mkdir(G5_DATA_PATH.'/qa', G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH.'/qa', G5_DIR_PERMISSION);

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

// 가변 파일 업로드
$file_upload_msg = '';
$upload = array();
for ($i=1; $i<=count($_FILES['bf_file']['name']); $i++) {
    $upload[$i]['file']     = '';
    $upload[$i]['source']   = '';
    $upload[$i]['del_check'] = false;

    // 삭제에 체크가 되어있다면 파일을 삭제합니다.
    if (isset($_POST['bf_file_del'][$i]) && $_POST['bf_file_del'][$i]) {
        $upload[$i]['del_check'] = true;
        @unlink(G5_DATA_PATH.'/qa/'.$write['qa_file'.$i]);
        // 썸네일삭제
        if(preg_match("/\.({$config['cf_image_extension']})$/i", $write['qa_file'.$i])) {
            delete_qa_thumbnail($write['qa_file'.$i]);
        }
    }

    $tmp_file  = $_FILES['bf_file']['tmp_name'][$i];
    $filesize  = $_FILES['bf_file']['size'][$i];
    $filename  = $_FILES['bf_file']['name'][$i];
    $filename  = get_safe_filename($filename);

    // 서버에 설정된 값보다 큰파일을 업로드 한다면
    if ($filename) {
        if ($_FILES['bf_file']['error'][$i] == 1) {
            $file_upload_msg .= '\"'.$filename.'\" 파일의 용량이 서버에 설정('.$upload_max_filesize.')된 값보다 크므로 업로드 할 수 없습니다.\\n';
            continue;
        }
        else if ($_FILES['bf_file']['error'][$i] != 0) {
            $file_upload_msg .= '\"'.$filename.'\" 파일이 정상적으로 업로드 되지 않았습니다.\\n';
            continue;
        }
    }

    if (is_uploaded_file($tmp_file)) {
        // 관리자가 아니면서 설정한 업로드 사이즈보다 크다면 건너뜀
        if (!$is_admin && $filesize > $qaconfig['qa_upload_size']) {
            $file_upload_msg .= '\"'.$filename.'\" 파일의 용량('.number_format($filesize).' 바이트)이 게시판에 설정('.number_format($qaconfig['qa_upload_size']).' 바이트)된 값보다 크므로 업로드 하지 않습니다.\\n';
            continue;
        }

        //=================================================================\
        // 090714
        // 이미지나 플래시 파일에 악성코드를 심어 업로드 하는 경우를 방지
        // 에러메세지는 출력하지 않는다.
        //-----------------------------------------------------------------
        $timg = @getimagesize($tmp_file);
        // image type
        if ( preg_match("/\.({$config['cf_image_extension']})$/i", $filename) ||
             preg_match("/\.({$config['cf_flash_extension']})$/i", $filename) ) {
            if ($timg['2'] < 1 || $timg['2'] > 16)
                continue;
        }
        //=================================================================

        if ($w == 'u') {
            // 존재하는 파일이 있다면 삭제합니다.
            @unlink(G5_DATA_PATH.'/qa/'.$write['qa_file'.$i]);
            // 이미지파일이면 썸네일삭제
            if(preg_match("/\.({$config['cf_image_extension']})$/i", $write['qa_file'.$i])) {
                delete_qa_thumbnail($row['qa_file'.$i]);
            }
        }

        // 프로그램 원래 파일명
        $upload[$i]['source'] = $filename;
        $upload[$i]['filesize'] = $filesize;

        // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
        $filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

        shuffle($chars_array);
        $shuffle = implode('', $chars_array);

        // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
        $upload[$i]['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

        $dest_file = G5_DATA_PATH.'/qa/'.$upload[$i]['file'];

        // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
        $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['bf_file']['error'][$i]);

        // 올라간 파일의 퍼미션을 변경합니다.
        chmod($dest_file, G5_FILE_PERMISSION);
    }
}

if($w == '' || $w == 'a' || $w == 'r') {
    if($w == '' || $w == 'r') {
        $row = sql_fetch(" select MIN(qa_num) as min_qa_num from {$g5['qa_content_table']} ");
        $qa_num = $row['min_qa_num'] - 1;
    }

    if($w == 'a') {
        $qa_num = $write['qa_num'];
        $qa_parent = $write['qa_id'];
        $qa_related = $write['qa_related'];
        $qa_category = $write['qa_category'];
        $qa_type = 1;
        $qa_status = 1;
    }

    $sql = " insert into {$g5['qa_content_table']}
                set qa_num          = '$qa_num',
                    mb_id           = '{$member['mb_id']}',
                    qa_name         = '".addslashes($member['mb_nick'])."',
                    qa_email        = '$qa_email',
                    qa_hp           = '$qa_hp',
                    qa_type         = '$qa_type',
                    qa_parent       = '$qa_parent',
                    qa_related      = '$qa_related',
                    qa_category     = '$qa_category',
                    qa_email_recv   = '$qa_email_recv',
                    qa_sms_recv     = '$qa_sms_recv',
                    qa_html         = '$qa_html',
                    qa_subject      = '$qa_subject',
                    qa_content      = '$qa_content',
                    qa_status       = '$qa_status',
                    qa_file1        = '{$upload[1]['file']}',
                    qa_source1      = '{$upload[1]['source']}',
                    qa_file2        = '{$upload[2]['file']}',
                    qa_source2      = '{$upload[2]['source']}',
                    qa_ip           = '{$_SERVER['REMOTE_ADDR']}',
                    qa_datetime     = '".G5_TIME_YMDHIS."',
                    qa_1            = '$qa_1',
                    qa_2            = '$qa_2',
                    qa_3            = '$qa_3',
                    qa_4            = '$qa_4',
                    qa_5            = '$qa_5' ";
    sql_query($sql);

    if($w == '' || $w == 'r') {
        $qa_id = sql_insert_id();

        if($w == 'r' && $write['qa_related']) {
            $qa_related = $write['qa_related'];
        } else {
            $qa_related = $qa_id;
        }

        $sql = " update {$g5['qa_content_table']}
                    set qa_parent   = '$qa_id',
                        qa_related  = '$qa_related'
                    where qa_id = '$qa_id' ";
        sql_query($sql);
    }

    if($w == 'a') {
        $sql = " update {$g5['qa_content_table']}
                    set qa_status = '1'
                    where qa_id = '{$write['qa_parent']}' ";
        sql_query($sql);
    }
} else if($w == 'u') {
    if(!$upload[1]['file'] && !$upload[1]['del_check']) {
        $upload[1]['file'] = $write['qa_file1'];
        $upload[1]['source'] = $write['qa_source1'];
    }

    if(!$upload[2]['file'] && !$upload[2]['del_check']) {
        $upload[2]['file'] = $write['qa_file2'];
        $upload[2]['source'] = $write['qa_source2'];
    }

    $sql = " update {$g5['qa_content_table']}
                set qa_email    = '$qa_email',
                    qa_hp       = '$qa_hp',
                    qa_category = '$qa_category',
                    qa_html     = '$qa_html',
                    qa_subject  = '$qa_subject',
                    qa_content  = '$qa_content',
                    qa_file1    = '{$upload[1]['file']}',
                    qa_source1  = '{$upload[1]['source']}',
                    qa_file2    = '{$upload[2]['file']}',
                    qa_source2  = '{$upload[2]['source']}',
                    qa_1        = '$qa_1',
                    qa_2        = '$qa_2',
                    qa_3        = '$qa_3',
                    qa_4        = '$qa_4',
                    qa_5        = '$qa_5' ";
    if($qa_sms_recv)
        $sql .= ", qa_sms_recv = '$qa_sms_recv' ";
    $sql .= " where qa_id = '$qa_id' ";
    sql_query($sql);
}

run_event('qawrite_update', $qa_id, $write, $w, $qaconfig);

// SMS 알림
if($config['cf_sms_use'] == 'icode' && $qaconfig['qa_use_sms']) {
    if($config['cf_sms_type'] == 'LMS') {
        include_once(G5_LIB_PATH.'/icode.lms.lib.php');

        $port_setting = get_icode_port_type($config['cf_icode_id'], $config['cf_icode_pw']);

        // SMS 모듈 클래스 생성
        if($port_setting !== false) {
            // 답변글은 질문 등록자에게 전송
            if($w == 'a' && $write['qa_sms_recv'] && trim($write['qa_hp'])) {
                $sms_content = $config['cf_title'].' '.$qaconfig['qa_title'].'에 답변이 등록되었습니다.';
                $send_number = preg_replace('/[^0-9]/', '', $qaconfig['qa_send_number']);
                $recv_number = preg_replace('/[^0-9]/', '', $write['qa_hp']);

                if($recv_number) {
                    $strDest     = array();
                    $strDest[]   = $recv_number;
                    $strCallBack = $send_number;
                    $strCaller   = iconv_euckr(trim($config['cf_title']));
                    $strSubject  = '';
                    $strURL      = '';
                    $strData     = iconv_euckr($sms_content);
                    $strDate     = '';
                    $nCount      = count($strDest);

                    $SMS = new LMS;
                    $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $port_setting);
                    $res = $SMS->Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $strDate, $nCount);

                    if($res) {
                        $SMS->Send();
                    }

                    $SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
                }
            }

            // 문의글 등록시 관리자에게 전송
            if(($w == '' || $w == 'r') && trim($qaconfig['qa_admin_hp'])) {
                $sms_content = $config['cf_title'].' '.$qaconfig['qa_title'].'에 문의글이 등록되었습니다.';
                $send_number = preg_replace('/[^0-9]/', '', $qaconfig['qa_send_number']);
                $recv_number = preg_replace('/[^0-9]/', '', $qaconfig['qa_admin_hp']);

                if($recv_number) {
                    $strDest     = array();
                    $strDest[]   = $recv_number;
                    $strCallBack = $send_number;
                    $strCaller   = iconv_euckr(trim($config['cf_title']));;
                    $strSubject  = '';
                    $strURL      = '';
                    $strData     = iconv_euckr($sms_content);
                    $strDate     = '';
                    $nCount      = count($strDest);

                    $SMS = new LMS;
                    $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $port_setting);
                    $res = $SMS->Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $strDate, $nCount);

                    if($res) {
                        $SMS->Send();
                    }

                    $SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
                }
            }
        }
    } else {
        include_once(G5_LIB_PATH.'/icode.sms.lib.php');

        // 답변글은 질문 등록자에게 전송
        if($w == 'a' && $write['qa_sms_recv'] && trim($write['qa_hp'])) {
            $sms_content = $config['cf_title'].' '.$qaconfig['qa_title'].'에 답변이 등록되었습니다.';
            $send_number = preg_replace('/[^0-9]/', '', $qaconfig['qa_send_number']);
            $recv_number = preg_replace('/[^0-9]/', '', $write['qa_hp']);

            if($recv_number) {
                $SMS = new SMS; // SMS 연결
                $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $config['cf_icode_server_port']);
                $SMS->Add($recv_number, $send_number, $config['cf_icode_id'], iconv("utf-8", "euc-kr", stripslashes($sms_content)), "");
                $SMS->Send();
            }
        }

        // 문의글 등록시 관리자에게 전송
        if(($w == '' || $w == 'r') && trim($qaconfig['qa_admin_hp'])) {
            $sms_content = $config['cf_title'].' '.$qaconfig['qa_title'].'에 문의글이 등록되었습니다.';
            $send_number = preg_replace('/[^0-9]/', '', $qaconfig['qa_send_number']);
            $recv_number = preg_replace('/[^0-9]/', '', $qaconfig['qa_admin_hp']);

            if($recv_number) {
                $SMS = new SMS; // SMS 연결
                $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $config['cf_icode_server_port']);
                $SMS->Add($recv_number, $send_number, $config['cf_icode_id'], iconv("utf-8", "euc-kr", stripslashes($sms_content)), "");
                $SMS->Send();
            }
        }
    }
}

// 답변 이메일전송
if($w == 'a' && $write['qa_email_recv'] && trim($write['qa_email'])) {
    include_once(G5_LIB_PATH.'/mailer.lib.php');

    $subject = $config['cf_title'].' '.$qaconfig['qa_title'].' 답변 알림 메일';
    $content = nl2br(conv_unescape_nl(stripslashes($qa_content)));

    mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $write['qa_email'], $subject, $content, 1);
}

// 문의글등록 이메일전송
if(($w == '' || $w == 'r') && trim($qaconfig['qa_admin_email'])) {
    include_once(G5_LIB_PATH.'/mailer.lib.php');

    $subject = $config['cf_title'].' '.$qaconfig['qa_title'].' 질문 알림 메일';
    $content = nl2br(conv_unescape_nl(stripslashes($qa_content)));

    mailer($config['cf_admin_email_name'], $qa_email, $qaconfig['qa_admin_email'], $subject, $content, 1);
}

if($w == 'a')
    $result_url = G5_BBS_URL.'/qaview.php?qa_id='.$qa_id.$qstr;
else if($w == 'u' && $write['qa_type'])
    $result_url = G5_BBS_URL.'/qaview.php?qa_id='.$write['qa_parent'].$qstr;
else
    $result_url = G5_BBS_URL.'/qalist.php'.preg_replace('/^&amp;/', '?', $qstr);

if ($file_upload_msg)
    alert($file_upload_msg, $result_url);
else
    goto_url($result_url);
*/
?>
