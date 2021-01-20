<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/style.css">', 0);
print_r($action_url)
?>

<section id="bo_w">
    <h2>1:1문의 작성</h2>
    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="qa_id" value="<?php echo $qa_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <?php
    $option = '';
    $option_hidden = '';
    $option = '';

    if ($is_dhtml_editor) {
        $option_hidden .= '<input type="hidden" name="qa_html" value="1">';
    } else {
        $option .= "\n".'<input type="checkbox" id="qa_html" name="qa_html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'>'."\n".'<label for="qa_html">html</label>';
    }

	echo $option_hidden;
    ?>
	
    <div class="form_01">
		<div class="row">
		<div class="col-md-4">
        <ul>
			<!-- 이름 -->
			<li class="">
				<label for="qa_name" class="">이름<strong class="sound_only">필수</strong></label>
				<input type="text" name="qa_name" value="" id="qa_name" size="39"  class="frm_input full_input " maxlength="255" placeholder="이름">
			</li>

			<!-- 주소 -->
			<li class="">
				<label for="qa_addr" class="">주소<strong class="sound_only">필수</strong></label>
				<input type="text" name="qa_addr" value="" id="qa_addr" size="39"  class="frm_input full_input " maxlength="255" placeholder="주소">
			</li>

			<!-- 희망대출금액 -->
			<li class="">
				<label for="qa_loan_amount" class="">희망대출금액<strong class="sound_only">필수</strong></label>
				<input type="text" name="qa_loan_amount" value="" id="qa_loan_amount" size="50"  class="frm_input full_input " maxlength="255" placeholder="희망대출금액">
			</li>
			
			<!-- 영업방법 -->
			<li class="">
				<label for="qa_business_manage" class="">영업방법<strong class="sound_only">필수</strong></label>
				<input type="text" name="qa_business_manage" value="" id="qa_business_manage" size="50"  class="frm_input full_input " maxlength="255" placeholder="영업방법">
			</li>

			<!-- 관리자 구분 -->
			<li class="">
				<label for="qa_admin_distin" class="">관리자구분<strong class="sound_only">필수</strong></label>
				<input type="text" name="qa_admin_distin" value="" id="qa_admin_distin" size="50"  class="frm_input full_input " maxlength="255" placeholder="관리자구분">
			</li>

			<!-- 상담사 -->
			<li class="">
				<label for="qa_counselor" class="">상담사<strong class="sound_only">필수</strong></label>
				<input type="input" name="qa_counselor" value="" id="qa_counselor" size="50"  class="frm_input full_input " maxlength="255" placeholder="상담사">
			</li>

			<!-- 상담내용 -->
			<li class="">
				<label for="qa_counsel_content" class="">상담내용<strong class="sound_only">필수</strong></label>
				<textarea value="" name="qa_counsel_content" id="qa_counsel_content"  class="frm_input full_input " rows="40" placeholder="상담내용">
				</textarea>
			</li>

            <?php if ($option) { ?>
            <li>
                옵션
                <?php echo $option; ?>
            </li>
            <?php } ?>

        </ul>
		</div>
		
	<!-- 두번째 column -->
	<div class="col-md-4">
        <ul>
			<!-- 접수방법 -->
   			<li class="">
				<label for="qa_receive_type" class="">접수방법<strong class="sound_only">필수</strong></label>
				<input type="text" name="qa_receive_type" value="" id="qa_receive_type" size="36"  class="frm_input full_input " maxlength="255" placeholder="접수방법">
			</li>
	
			<!-- 전화 -->
			<li class="">
				<label for="qa_tele_num" class="">전화<strong class="sound_only">필수</strong></label>
				<input type="text" name="qa_tele_num" value="" id="qa_tele_num" size="39"  class="frm_input full_input " maxlength="255" placeholder="전화">
			</li>
			
			<!-- 제3자 정보 제공 동의 -->
			<li class="">
				<label for="qa_third_confirm" class="full_input">제3자정보제공동의<strong class="sound_only full_input">필수</strong></label>
				<input type="radio" name="qa_third_confirm"  value="agree" id="qa_third_agree"   class="frm_input " maxlength="255" placeholder="제3자정보제공동의">
				<label for="qa_third_confirm">동의</label>
				<input type="radio" name="qa_third_confirm"  value="disagree" id="qa_third_disagree"   class="frm_input " maxlength="255" placeholder="제3자정보제공동의">
				<label for="qa_third_disagree">비동의</label>
			</li>
			<!-- 신청날짜 -->
			<li class="">
				<label for="qa_reg_date" class="">신청날짜<strong class="sound_only">필수</strong></label>
				<input type="text" name="qa_reg_date" value="" id="qa_reg_date" size="50"  class="frm_input full_input " maxlength="255" placeholder="신청날짜">
			</li>
			<!-- URL참조 -->
			<li class="">
				<label for="qa_url_refer" class="">URL참조<strong class="sound_only">필수</strong></label>
				<input type="text" name="qa_url_refer" value="" id="qa_url_refer" size="50"  class="frm_input full_input " maxlength="255" placeholder="URL참조">
			</li>
			<!-- 관리자정보 -->
			<li class="">
				<label for="" class="full_input">--관리자정보--<strong class="sound_only">필수</strong></label>
				<div class="qa_information_wrap">
				<!-- 어드민 아이디 -->
				<label for="admin_id">어드민 아이디</label>
				<input type="text" name="admin_id" value="" id="admin_id" size="31"  class="frm_input full_input " maxlength="255" placeholder="AdminID">
				<!-- 마스터 아이디 -->
				<label for="master_id">마스터 아이디</label>
				<input type="text" name="master_id" value="" id="master_id" size="31"  class="frm_input full_input " maxlength="255" placeholder="MasterID">
				<!-- 부서 아이디 -->
				<label for="section_id">부서 아이디</label>
				<input type="text" name="section_id" value="" id="section_id" size="31"  class="frm_input full_input " maxlength="255" placeholder="SectionID">
				</div>
			</li>
			</ul>
			</div>

	<!-- 세번째 column -->
	<div class="col-md-4">
		<ul>
			<!-- 대출종류 -->
			<!-- DB연동시 key값을 가지고와서 $key_type에 알맞은 값을 할당하는 작업이 필요하다 -->
			<!-- 현재는 DB에서 (아파트담보대출, 신용대출, )등 여러가지가 있는데 이를 $consult_type_option변수 배열의 키로 비교하고 있다 하지만 DB에 저장될 값을 키가 아닌 데이터 값이다 이것을 어떻게 해결할 것인지 해답 필요 (DB에 저장할때는 key값을 저장) -->
			<li class="">
				<label for="qa_loan_type" class="">대출종류<strong class="sound_only">필수</strong></label>
				<select class="frm_input full_input" id="qa_loan_type"name="qa_loan_type">
				<?php foreach($consult_type_option as $key_type=> $data){ ?>
					<option value="<?php echo $key_type ?>" <?php echo ($key_type == "1") ? "selected" : "" ?>><?php print_r( $data )?></option>
				<?php } ?>
				</select>
			</li>

			<!-- DB연동시 key값을 가지고와서 $key_reg에 알맞은 값을 할당하는 작업이 필요하다 -->
			<!-- 지역선택하는 메뉴관련 -->
			<li class="">
				<label for="qa_region" class="">지역<strong class="sound_only">필수</strong></label>
				<select class="frm_input full_input" id="qa_region" name="qa_region">
				<?php foreach($consult_region_option as $key_reg => $data_reg){ ?>
					<option value="<?php echo $key_reg ?>" <?php echo ($key_reg == "1") ? "selected":""?>><?php print_r($data_reg)?></option>
				<?php }?>
				</select>
			</li>
			<!-- 첨부파일 메뉴 -->
			<li class="">
				<label for="qa_file" class="">첨부파일<strong class="sound_only">필수</strong></label>
				<input type="file" name="qa_file" value="" id="qa_file" size="50"  class="frm_input full_input " maxlength="255" placeholder="첨부파일">
			</li>
			
			<!-- DB연동시 key값을 가지고와서 $key_prog에 알맞은 값을 할당하는 작업이 필요하다 -->
			<!-- 진행상태 --><!-- 현재 진행상태 배열 그리고 반복문 사용해서 진행상태 표시하기 (진행상태 배열은 $consult_progress_state)-->
			<li class="">
				<label for="qa_progress" class="">진행상태<strong class="sound_only">필수</strong></label>
				<select class="frm_input full_input" id="qa_progress" name="qa_progress">
				<?php foreach($consult_progress_state as $key_prog => $data_prog){ ?>
					<option value="<?php echo $key_prog ?>" <?php echo ($key_prog=="1") ? "selected":""?>><?php print_r($data_prog)?></option>
				<?php }?>
				</select>
			</li>

			<!-- 접속 URL -->
			<li>
			<label for="" class="">접속 URL<strong class="sound_only">필수</strong></label>
				<!-- DB연동시 key값을 가지고와서 $key_site에 알맞은 값을 할당하는 작업이 필요하다 -->
				<!-- 반복문과 배열($consult_site_option)을 사용하여 접속URL값 표시하기 (bizconsult의 경우 상담추가 페이지에서 사이트 선택하는 구간에서 접속 URL을 선택한다. -->
				<select class="frm_input full_input" id="qa_access_url">
				<?php foreach($consult_site_option as $key_site => $data_site){ ?>
					<option value="<?php echo $key_site ?>" <?php echo ($key_site=="1") ? "selected":""?>><?php print_r($data_site)?></option>
				<?php }?>
				</select>
			</li>

			<!-- 알림날짜 -->
			<li class="qa_recei_type_wrap">
				<label for="qa_no_date" class="">알림날짜<strong class="sound_only">필수</strong></label>
				<input type="text" name="qa_no_date" value="" id="qa_no_date" size="50"  class="frm_input full_input " maxlength="255" placeholder="알림날짜">
			</li>
		</ul>
	</div>
		</div>
   </div>

    
    <div class="btn_confirm write_div">
        <a href="<?php echo $list_href; ?>" class="btn_cancel btn">취소</a>
        <button type="submit" id="btn_submit" accesskey="s" class="btn_submit btn">작성완료</button>
    </div>
    
    </form>

    <script>
    function html_auto_br(obj)
    {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "2";
            else
                obj.value = "1";
        }
        else
            obj.value = "";
    }

    function fwrite_submit(f)
    {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

        var subject = "";
        var content = "";
        $.ajax({
            url: g5_bbs_url+"/ajax.filter.php",
            type: "POST",
            data: {
                "subject": f.qa_subject.value,
                "content": f.qa_content.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                subject = data.subject;
                content = data.content;
            }
        });

        if (subject) {
            alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
            f.qa_subject.focus();
            return false;
        }

        if (content) {
            alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
            if (typeof(ed_qa_content) != "undefined")
                ed_qa_content.returnFalse();
            else
                f.qa_content.focus();
            return false;
        }

        <?php if ($is_hp) { ?>
        var hp = f.qa_hp.value.replace(/[0-9\-]/g, "");
        if(hp.length > 0) {
            alert("휴대폰번호는 숫자, - 으로만 입력해 주십시오.");
            return false;
        }
        <?php } ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>
</section>
<!-- } 게시물 작성/수정 끝 -->
