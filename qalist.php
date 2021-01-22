<?php
include_once('./_common.php');
if($is_guest)
    alert('회원이시라면 로그인 후 이용해 보십시오.', './login.php?url='.urlencode(G5_BBS_URL.'/qalist.php'));

$qaconfig = get_qa_config();

$g5['title'] = $qaconfig['qa_title'];
include_once('./qahead.php');

$skin_file = $qa_skin_path.'/list.skin.php';

$category_option = '';
if ($qaconfig['qa_category']) {
    $category_href = G5_BBS_URL.'/qalist.php';

    $category_option .= '<li><a href="'.$category_href.'"';
    if ($sca=='')
        $category_option .= ' id="bo_cate_on"';
    $category_option .= '>전체</a></li>';

    $categories = explode('|', $qaconfig['qa_category']); // 구분자가 | 로 되어 있음
    for ($i=0; $i<count($categories); $i++) {
        $category = trim($categories[$i]);
        if ($category=='') continue;
        $category_msg = '';
        $category_option .= '<li><a href="'.($category_href."?sca=".urlencode($category)).'"';
        if ($category==$sca) { // 현재 선택된 카테고리라면
            $category_option .= ' id="bo_cate_on"';
            $category_msg = '<span class="sound_only">열린 분류 </span>';
        }
        $category_option .= '>'.$category_msg.$category.'</a></li>';
    }
}

if(is_file($skin_file)) {
	/*
    $sql_common = " from {$g5['qa_content_table']} ";
    $sql_search = " where qa_type = '0' ";

    if(!$is_admin)
        $sql_search .= " and mb_id = '{$member['mb_id']}' ";

    if($sca) {
        if (preg_match("/[a-zA-Z]/", $sca))
            $sql_search .= " and INSTR(LOWER(qa_category), LOWER('$sca')) > 0 ";
        else
            $sql_search .= " and INSTR(qa_category, '$sca') > 0 ";
    }

    $stx = trim($stx);
    if($stx) {
        if (preg_match("/[a-zA-Z]/", $stx))
            $sql_search .= " and ( INSTR(LOWER(qa_subject), LOWER('$stx')) > 0 or INSTR(LOWER(qa_content), LOWER('$stx')) > 0 )";
        else
            $sql_search .= " and ( INSTR(qa_subject, '$stx') > 0 or INSTR(qa_content, '$stx') > 0 ) ";
    }
	*/
	

	
    //$sql_order = " order by qa_num ";

	//////////------여기서부터가 내가 짜는 코드
	$sql = "select count(*) as cnt from g5_consult;";
	$row = sql_fetch($sql);
	$totalCount = $row['cnt'];
	$pageRows = 40;//하나의 페이지에 40개의 rows를 출력한다
	$totalPage = ceil($totalCount / $pageRows);//전페 페이지 계산

	$currentPage = (isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 1;
	// Prev + Next   pagination을 위한 변수들
	$prev = $page - 1;
	$next = $page + 1;
	
	if ($page < 1) {$page = 1;}//페이지가 없으면 처 페이지 (1 페이지)
	$fromRecord = ($page - 1) * $page_rows;//시작 열을 구함
	$sql = "SELECT * FROM g5_consult ORDER BY cId DESC LIMIT $fromRecord, $pageRows;";
	$result = sql_query($sql);
	//print_r($result);
	$list = array();
	$num = $totalCount -($page - 1) * $pageRows;
	for($i=0; $row=sql_fetch_array($result);$i++) {
		$list[$i]= $row;
		$list[$i]['num'] = $num - $i;//게시판에 출력딜 번호
		$loanTypeNum = get_text($row['loTyp']);
		$list[$i]['loanType'] = get_text($consult_type_option[$loanTypeNum]);//대출종류 -- select menu이기 때문에 key값을 가지고 와서 이를 원래의 문자값으로 변경하는 것이 필요
		
		$list[$i]['name'] = get_text($row['Name']);//이름
		$list[$i]['phoneNumber'] = get_text($row['pNum']);//전화번호
		$list[$i]['loanAmount'] = get_text($row['reqLoaAmoun']);//희망대출금액
		$regionNum = get_text($row['regi']);
		$list[$i]['region'] = get_text($consult_region_option[$regionNum]);//지역 -- select menu이기 때문에 key값을 가지고 와서 이를 원래의 문자값으로 변경하는 것이 필요
		$list[$i]['requestDate'] = get_text($row['regDa']);//신청날짜
		$progressNum = get_text($row['prog']);
		$list[$i]['progress'] = get_text($consult_progress_state[$progressNum]);//진행상태 -- select menu이기 때문에 key값을 가지고 와서 이를 원해의 문자값으로 변경하는 것이 필요
		
		$list[$i]['lastUpdate'] = get_text($row['updatedAt']);//최종업데이트날짜
		$list[$i]['counselor'] = get_text($row['counse']);//상담사
		
		$list[$i]['memo'] = get_text($row['counCon']);//메모    게시판에서 메모는 write페이지에서 입력했던 상담내용을 출력하면 된다.
		//echo $list[$i]['name'];
		//qaview.php에서 사용할 cId값을 할당
		$list[$i]['cId'] = get_text($row['cId']);
	}

	//print_r($list);// 여기에서 가지고 오는 배열이 빈 문자열
	//////////------여기까지가 내가 짜는 코드  /// 테스트시 아래부분 주석처리 필요
	/*
	$sql = " select count(*) as cnt
                $sql_common
                $sql_search ";
	
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];
	//echo "total_count:".$total_count;
	//하나의 페이지에 40개의 줄을 출력한다.
	$page_rows  = 40;
//    $page_rows = G5_IS_MOBILE ? $qaconfig['qa_mobile_page_rows'] : $qaconfig['qa_page_rows'];
    $total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
    if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
    $from_record = ($page - 1) * $page_rows; // 시작 열을 구함

    $sql = " select *
                $sql_common
                $sql_search
                $sql_order
                limit $from_record, $page_rows ";
    $result = sql_query($sql);

    $list = array();
    $num = $total_count - ($page - 1) * $page_rows;
    $subject_len = G5_IS_MOBILE ? $qaconfig['qa_mobile_subject_len'] : $qaconfig['qa_subject_len'];
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $list[$i] = $row;

        $list[$i]['category'] = get_text($row['qa_category']);
        $list[$i]['subject'] = conv_subject($row['qa_subject'], $subject_len, '…');
        if ($stx) {
            $list[$i]['subject'] = search_font($stx, $list[$i]['subject']);
        }

        $list[$i]['view_href'] = G5_BBS_URL.'/qaview.php?qa_id='.$row['qa_id'].$qstr;

        $list[$i]['icon_file'] = '';
        if(trim($row['qa_file1']) || trim($row['qa_file2']))
            $list[$i]['icon_file'] = '<img src="'.$qa_skin_url.'/img/icon_file.gif">';

        $list[$i]['name'] = get_text($row['qa_name']);
        // 사이드뷰 적용시
        //$list[$i]['name'] = get_sideview($row['mb_id'], $row['qa_name']);
        $list[$i]['date'] = substr($row['qa_datetime'], 2, 8);

        $list[$i]['num'] = $num - $i;
    }
	*/
    $is_checkbox = false;
    $admin_href = '';
    if($is_admin) {
        $is_checkbox = true;
        $admin_href = G5_ADMIN_URL.'/qa_config.php';
    }

    $list_href = G5_BBS_URL.'/qalist.php';
    $write_href = G5_BBS_URL.'/qawrite.php';

    $list_pages = preg_replace('/(\.php)(&amp;|&)/i', '$1?', get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './qalist.php'.$qstr.'&amp;page='));

    $stx = get_text(stripslashes($stx));

    include_once($skin_file);
} else {
    echo '<div>'.str_replace(G5_PATH.'/', '', $skin_file).'이 존재하지 않습니다.</div>';
}

include_once('./qatail.php');
?>
