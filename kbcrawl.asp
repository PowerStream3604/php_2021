Dim strHeaders

Class XMLDOMParse   
   Private m_DOM ' XMLDOM 객체
   ' ---------------------- 생성자 -----------------------
   Private Sub Class_Initialize()
      Set m_DOM = WScript.CreateObject("Microsoft.XMLDOM")
   End Sub

   ' ---------------------- 소멸자 -----------------------
   Private Sub Class_Terminate()
      Set m_DOM = Nothing
   End Sub
   ' ------------------- Property Get --------------------
   Public Property Get TagText(tagName, index)
      On Error Resume Next
      Dim Nodes

      Set Nodes = m_DOM.getElementsByTagName(tagName)
      'response.write Nodes
      TagText = Nodes(index).Text
      If Err.Number <> 0 Then
         TagText = "Not Found"
      End if
      Set Nodes = Nothing
   End Property

   ' ------------------- Property Get --------------------
   Public  Property Get AttributeText(tagName, index , item)
      AttributeText =  m_DOM.getElementsByTagName(tagName)(index).attributes.getNamedItem(item).Text
   End Property

   ' ------------------- 원격 XML 읽기 --------------------
   Public Function LoadHTTP(url)
      with m_DOM
         .async = False ' 동기식 호출
         .setProperty "ServerHTTPRequest", True ' HTTP로 XML 데이터 가져옴

         LoadHTTP = .Load(url)
      end with 
   End Function

   ' -------------------  XML 읽기 --------------------
   Public Function Load(strXML )
      with m_DOM
         .async = False ' 동기식 호출
         .loadXML(strXML)
      end with 
   End Function
   ' -------------------  HTML 페이지  읽기 --------------------
   Public Function  OpenHttp( PgURL) 
      Set xmlHttp = Server.CreateObject("Microsoft.XMLHTTP") 
      xmlHttp.Open "GET", PgURL, False 
      xmlHttp.Send 
      Ret =  xmlHttp.ResponseText
      Set xmlHttp = Nothing
      OpenHttp = Ret
   End Function
End Class

Sub Sleep(intSeconds) 
    dteStart = Time() 
    dteEnd = DateAdd("s", intSeconds, dteStart)  

    While dteEnd > Time() 
        DoNothing 
    Wend
End Sub 

Sub DoNothing
    'While/Wend has quirks when it is empty
End Sub

Function GetDB()
   Dim FCDB, FCSTR
   Set   FCDB = WScript.CreateObject("ADODB.Connection")
   FCSTR = "Provider=SQLOLEDB;Data Source=127.0.0.1;Initial Catalog=bizconsult;user ID=bizuser1;password=dhehqkd!!22;"
   FCDB.Open(FCSTR)
   
   Set GetDB = FCDB
End Function


Set rootdb = GetDB()

Set xobj = CreateObject("SOFTWING.ASPtear")
Request_POST = 1
Request_GET = 2
xobj.ForceReload = True
xobj.FollowRedirects = True
xobj.ConnectionTimeout = 10000
'xobj.Proxy = "http://182.50.155.3:808"

Function GetPage(url, param, charset)
   On Error Resume Next
   'xobj.AddHeader "User-Agent", "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; InfoPath.2; .NET CLR 2.0.50727)"
   xobj.AddHeader "Content-Type", "text/html; charset=UTF-8"
   GetPage = xobj.Retrieve(url, Request_POST, param, "", "")
   If Err.Number <> 0 Then
      GetPage = GetPage(url)
      Exit Function
   End If 
End Function

Function BinaryToText(BinaryData, CharSet)
   Const adTypeText = 2
   Const adTypeBinary = 1

   Dim BinaryStream
   Set BinaryStream = CreateObject("ADODB.Stream")

   BinaryStream.Type = adTypeBinary
   BinaryStream.open
   BinaryStream.Write BinaryData
   BinaryStream.Position = 0
   BinaryStream.Type = adTypeText
   BinaryStream.Charset = CharSet
   BinaryToText = BinaryStream.ReadText
End Function

Function getContents(url, param, charset)
   On Error Resume Next
   'url = "http://182.50.155.3/red.asp?url=" & url
   Set httpobj = WScript.CreateObject("WinHttp.WinHttpRequest.5.1")
   'httpobj.setproxy 2, "182.50.155.3:808", ""
   httpobj.open "POST",url, True   
   httpobj.setRequestHeader "Content-Type", "application/x-www-form-urlencoded;  charset=UTF-8"
   httpobj.setRequestHeader "Referer", "http://nland.kbstar.com/quics?page=B025914&cc=b043428:b043506"
   httpobj.Send param
   httpobj.WaitForResponse
   strHeaders = httpobj.GetAllResponseHeaders 

   If Err.Number <> 0 Then
      wscript.echo Err.Number & " retry.."
      getContents = getContents(url)
      Exit Function
   End If 
   If httpobj.status ="200" Then
      getContents = BinaryToText(httpobj.ResponseBody, charset)
   ElseIf httpobj.status >= 500 and status <= 600 Then
      getContents ="500 error"
   Else
      getContents = httpobj.status  & " error"
   End If
   Set httpobj = Nothing
End Function

Function ExtractStr(linestr, startchar, endchar)
   nFoundAt = InStr(linestr, startchar)
   If (nFoundAt > 0) Then
      If endchar = "%END%" Then
         nEndOfCookie = Len(linestr) + 1
      else
         nEndOfCookie = InStr(nFoundAt+1, linestr, endchar)
      End If 
      If (nEndOfCookie > 0) Then
         ExtractStr = Mid(linestr, nFoundAt + Len(startchar), nEndOfCookie - nFoundAt - Len(startchar))
      Else
         ExtractStr = ""
      End If
   Else
      ExtractStr = ""
   End If
End Function 

strYear = Year(Date)
strMonth = Month(Date)
strDay = Day(Date)
strHour = Hour(Time)
strMinute = Minute(Time)
strSecond = Second(Time)
If strMonth < 10 Then strMonth = "0" & strMonth
If strDay < 10 Then strDay = "0" & strDay
If strHour < 10 Then strHour = "0" & strHour
If strMinute < 10 Then strMinute = "0" & strMinute
If strSecond < 10 Then strSecond = "0" & strSecond
uniqueid = strYear & strMonth & strDay & strHour & strHour & strSecond & random(4)

Dim aryDo(17)
Dim aryDoCode(17)
aryDo(0) = "서울특별시"
aryDo(1) = "경기도"
aryDo(2) = "인천광역시"
aryDo(3) = "부산광역시"
aryDo(4) = "대전광역시"
aryDo(5) = "대구광역시"
aryDo(6) = "광주광역시"
aryDo(7) = "울산광역시"
aryDo(8) = "강원도"
aryDo(9) = "충청남도"
aryDo(10) = "충청북도"
aryDo(11) = "경상남도"
aryDo(12) = "경상북도"
aryDo(13) = "전라남도"
aryDo(14) = "전라북도"
aryDo(15) = "제주특별자치도"
aryDo(16) = "세종특별자치시"
aryDoCode(0) = "010000"
aryDoCode(1) = "020000"
aryDoCode(2) = "030000"
aryDoCode(3) = "040000"
aryDoCode(4) = "050000"
aryDoCode(5) = "060000"
aryDoCode(6) = "070000"
aryDoCode(7) = "080000"
aryDoCode(8) = "090000"
aryDoCode(9) = "100000"
aryDoCode(10) = "110000"
aryDoCode(11) = "120000"
aryDoCode(12) = "130000"
aryDoCode(13) = "140000"
aryDoCode(14) = "150000"
aryDoCode(15) = "160000"
aryDoCode(16) = "170000"

Set pxml = new XMLDOMParse
For i = 0 To 16
   strResult = getContents("http://nland.kbstar.com/quics?page=B025914&cc=b043428:b043506", "대지역코드=" & aryDoCode(i) & "&대지역명=" & aryDo(i) & "&중지역코드=&중지역명=&소지역코드=&소지역명=&물건식별자=&단지명=&매물일련번호=&물건유형구분=A&탭구분코드=S&지역검색탭=S&물건종별그룹구분=&지도우측영역=시구선택단계&시세매물동코드=010401%7C010403%7C010405%7C010406%7C010407%7C010408%7C010409%7C010413&물건좌표=&지역선택단계=1&마우스오버정보구분=&소구역코드=&cc대상컴포넌트=b043506&주변도시선택아이디=&ASP페이지코드=&매물목록단계=2&goodsType=A&area1=" & aryDoCode(i) & "&area2=010400&area3=&area4=", "UTF-8")
wscript.echo "http://nland.kbstar.com/quics?page=B025914&cc=b043428:b043506", "대지역코드=" & aryDoCode(i) & "&대지역명=" & aryDo(i) & "&중지역코드=&중지역명=&소지역코드=&소지역명=&물건식별자=&단지명=&매물일련번호=&물건유형구분=A&탭구분코드=S&지역검색탭=S&물건종별그룹구분=&지도우측영역=시구선택단계&시세매물동코드=010401%7C010403%7C010405%7C010406%7C010407%7C010408%7C010409%7C010413&물건좌표=&지역선택단계=1&마우스오버정보구분=&소구역코드=&cc대상컴포넌트=b043506&주변도시선택아이디=&ASP페이지코드=&매물목록단계=2&goodsType=A&area1=" & aryDoCode(i) & "&area2=010400&area3=&area4="
   aryLine = Split(strResult, Chr(10))
   For j = 0 To UBound(aryLine)
      If InStr(aryLine(j), " onchange=""javascript:chgArea2()") > 0 Then
         areaLine = aryLine(j + 2)
         aryGu = Split(areaLine, "</option>")
         For k = 0 To UBound(aryGu) - 1
            gucode = ExtractStr(aryGu(k), "value=""", """>")
            guname = ExtractStr(aryGu(k), ">", "%END%")
            wscript.echo gucode
            getDongList aryDoCode(i), aryDo(i), gucode, guname
         Next 
      End If 
   Next 
Next 

sql = "delete from kbsisenew where uniqueid <> '" & uniqueid & "'"
'rootdb.execute(sql)

Function getDongList(docode, doname, gucode, guname)
   strResult = getContents("http://nland.kbstar.com/quics?page=B025914&cc=b043428:b043506", "대지역코드=" & docode & "&대지역명=" & doname & "&중지역코드=" & gucode & "&중지역명=" & guname & "&소지역코드=&소지역명=&물건식별자=&단지명=&매물일련번호=&물건유형구분=A&탭구분코드=S&지역검색탭=S&물건종별그룹구분=&지도우측영역=시구선택단계&시세매물동코드=&물건좌표=&지역선택단계=2&마우스오버정보구분=&소구역코드=&cc대상컴포넌트=b043506&주변도시선택아이디=&ASP페이지코드=&매물목록단계=2&goodsType=A&area1=" & docode & "&area2=" & gucode & "&area3=&area4=", "UTF-8")

   aryLine2 = Split(strResult, Chr(10))
   For l = 0 To UBound(aryLine2)
      If InStr(aryLine2(l), " onchange=""javascript:chgArea3()") > 0 Then
         areaLine = aryLine2(l + 2)
         aryDong = Split(areaLine, "</option>")
         For m = 0 To UBound(aryDong) - 1
            dongcode = ExtractStr(aryDong(m), "value=""", """>")
            dongname = ExtractStr(aryDong(m), ">", "%END%")
            getApartList docode, doname, gucode, guname, dongcode, dongname
         Next 
      End If 
   Next 
End Function

Function getApartList(docode, doname, gucode, guname, dongcode, dongname)
   strResult = getContents("http://nland.kbstar.com/quics?page=B025914&cc=b043428:b043506", "대지역코드=" & docode & "&대지역명=" & doname & "&중지역코드=" & gucode & "&중지역명=" & guname & "&소지역코드=" & dongcode & "&소지역명=" & dongname & "&물건식별자=&단지명=&매물일련번호=&물건유형구분=A&탭구분코드=S&지역검색탭=S&물건종별그룹구분=&지도우측영역=시구선택단계&시세매물동코드=&물건좌표=&지역선택단계=3&마우스오버정보구분=&소구역코드=&cc대상컴포넌트=b043506&주변도시선택아이디=&ASP페이지코드=&매물목록단계=2&goodsType=A&area1=" & docode & "&area2=" & gucode & "&area3=" & dongcode & "&area4=", "UTF-8")

   aryLine3 = Split(strResult, Chr(10))
   For n = 0 To UBound(aryLine3)
      If InStr(aryLine3(n), " onchange=""javascript:chgArea4()") > 0 Then
         areaLine = aryLine3(n + 2)
         aryApart = Split(areaLine, "</option>")
         For o = 0 To UBound(aryApart) - 1
            apartcode = ExtractStr(aryApart(o), "value=""", """>")
            apartname = ExtractStr(aryApart(o), ">", "%END%")
            getApartSise docode, doname, gucode, guname, dongcode, dongname, apartcode, apartname
         Next 
      End If 
   Next 
End Function

Function getApartSise(docode, doname, gucode, guname, dongcode, dongname, apartcode, apartname)
   ipjoo = ""
   oldaddress = ""
   newaddress = ""
   sedesoo = ""
   dongsoo = ""
   stories1 = ""
   stories2 = ""
   manageoffice = ""

   strResult = getContents("http://nland.kbstar.com/quics?page=B025914&cc=b043428:b043506", "중지역명=" & guname & "&소구역코드=&지역선택단계=4&매물목록단계=4&물건좌표=&마우스오버정보구분=&소지역코드=" & dongcode & "&매물일련번호=&물건유형구분=A&대지역코드=" & docode & "&area4=" & apartcode & "&중지역코드=" & gucode & "&area3=" & dongcode & "&area2=" & gucode & "&area1=" & docode & "&지역검색탭=S&조회구분=3&RESCODE=111&물건식별자=" & apartcode & "&단지명=" & apartname & "&cc대상컴포넌트=b043506&ASP페이지코드=&시세매물동코드=010501%7C010502%7C010503&소지역명=" & dongname & "&주변도시선택아이디=&물건종별그룹구분=&goodsType=A&지도우측영역=동단지선택단계&대지역명=" & doname & "&B_CLEAR=1&탭구분코드=S&탭구분코드2=S&화면스크롤값=0&시세탭선택여부=1", "UTF-8")

   aryLine4 = Split(strResult, Chr(10))
   For line = 0 To UBound(aryLine4)
      If InStr(aryLine4(line), "link_direct"" onclick=""goHscmDtlInfoPage") > 0 Then
         area1 = ExtractStr(aryLine4(line), ";"">", "%END%")
         area1 = Left(area1, Len(area1) - 1)
         area2 = ExtractStr(aryLine4(line), "전용면적", "㎡")

         lowsalesprice = ExtractStr(aryLine4(line+4), """>", "%END%")
         lowsalesprice = Left(lowsalesprice, Len(lowsalesprice) - 1)
         midsalesprice = ExtractStr(aryLine4(line+6), """>", "%END%")
         midsalesprice = Left(midsalesprice, Len(midsalesprice) - 1)
         highsalesprice = ExtractStr(aryLine4(line+8), """>", "%END%")
         highsalesprice = Left(highsalesprice, Len(highsalesprice) - 1)
         lowrentprice = ExtractStr(aryLine4(line+10), """>", "%END%")
         lowrentprice = Left(lowrentprice, Len(lowrentprice) - 1)
         midrentprice = ExtractStr(aryLine4(line+12), """>", "%END%")
         midrentprice = Left(midrentprice, Len(midrentprice) - 1)
         highrentprice = ExtractStr(aryLine4(line+14), """>", "%END%")
         highrentprice = Left(highrentprice, Len(highrentprice) - 1)

         If ipjoo = "" Then
            strResult = getContents("http://nland.kbstar.com/quics?chgCompId=b043125&baseCompId=b043428&page=B025914&cc=b043428:b043125", "탭영역구분=종합매물&검색구분=&매물목록단계=3&동일주택형구분내용=&부동산광역지역코드=&페이지목록수=&지하철일련번호=&매물가격=&소지역코드=" & dongcode & "&매물면적시작=&SmlArea=" & dongname & "&재개발여부=&월세보증금시작=&입주년월끝=&매물수익률=&매물일련번호=&학교코드=&주택형일련번호=1&페이지번호=&대지역코드=" & docode & "&지하철역명칭=&조회구분=3&중지역명=" & guname & "&중지역코드=" & gucode & "&관심목록건수=0&B_CLEAR=1&s물건종별그룹구분=&물건식별자=" & apartcode & "&전세가시작=&단지명F=" & apartname & "&정렬기준구분명4=&정렬기준구분명3=&정렬기준구분명2=&월세가=&정렬기준구분명1=&LarArea=" & doname & "&물건종별그룹구분=&대지역명=" & doname & "&Ju=1&페이지번호4=&페이지번호3=&세대수시작=&페이지번호2=&페이지번호1=&도시형생활주택여부=&지하철지역구분=&RESCODE=111&세대수끝=&Danji=" & apartcode & "&거래구분=&지역3=&지역2=&지역1=&지하철호선구분=&월세가시작=&단지명=" & apartname & "&세대수=&전세가끝=&월세보증금끝=&월세가끝=&지역선택단계=4&s매물거래구분=&지역검색탭=S&비교매물4=&비교매물3=&비교매물2=&전세가=&비교매물1=&대학교명=&매매가시작=&주택형F=" & area1 & "%2F" & area2 & "&월세보증금=&매매가=&소지역명=" & dongname & "&재건축여부=&물건유형구분=A&매물면적=&주상복합여부=&매매가끝=&매물면적끝=&입주년월=&MidArea=" & guname & "&매물유형구분=&입주년월시작=&탭구분코드=D&탭구분코드2=D&화면스크롤값=0&시세탭선택여부=0", "UTF-8")

            aryLine5 = Split(strResult, Chr(10))
            For tmp = 0 To UBound(aryLine5)
               If InStr(aryLine5(tmp), ">입주년월</") > 0 Then ipjoo = Trim(Replace(Replace(aryLine5(tmp + 5), Chr(9), ""), "'", "`"))
               If InStr(aryLine5(tmp), ">지번주소</") > 0 Then oldaddress = Trim(Replace(Replace(aryLine5(tmp + 6), Chr(9), ""), "'", "`"))
               If InStr(aryLine5(tmp), ">도로명주소</") > 0 Then newaddress = Trim(Replace(Replace(aryLine5(tmp + 5), Chr(9), ""), "'", "`"))
               If InStr(aryLine5(tmp), ">총세대수</") > 0 Then sedesoo = Trim(Replace(Replace(aryLine5(tmp + 5), Chr(9), ""), "'", "`"))
               If InStr(aryLine5(tmp), ">총동수</") > 0 Then dongsoo = Trim(Replace(Replace(aryLine5(tmp + 5), Chr(9), ""), "'", "`"))
               If InStr(aryLine5(tmp), ">최고층수</") > 0 Then stories1 = Trim(Replace(Replace(aryLine5(tmp + 5), Chr(9), ""), "'", "`"))
               If InStr(aryLine5(tmp), ">최저층수</") > 0 Then stories2 = Trim(Replace(Replace(aryLine5(tmp + 5), Chr(9), ""), "'", "`"))
               If InStr(aryLine5(tmp), ">관리사무소 연락처<") > 0 Then manageoffice = Trim(Replace(Replace(aryLine5(tmp + 5), Chr(9), ""), "'", "`"))
            Next 
            ipjoo = Replace(ipjoo, Chr(13), "")
            oldaddress = Replace(oldaddress, Chr(13), "")
            newaddress = Replace(newaddress, Chr(13), "")
            sedesoo = Replace(sedesoo, Chr(13), "")
            dongsoo = Replace(dongsoo, Chr(13), "")
            stories1 = Replace(stories1, Chr(13), "")
            stories2 = Replace(stories2, Chr(13), "")
            manageoffice = Replace(manageoffice, Chr(13), "")
         End If 

         apartname = Replace(apartname, "'", "`")
         lowsalesprice = Replace(lowsalesprice, ",", "")
         midsalesprice = Replace(midsalesprice, ",", "")
         highsalesprice = Replace(highsalesprice, ",", "")
         lowrentprice = Replace(lowrentprice, ",", "")
         midrentprice = Replace(midrentprice, ",", "")
         highrentprice = Replace(highrentprice, ",", "")
         If lowsalesprice = "-" Then lowsalesprice = 0
         If midsalesprice = "-" Then midsalesprice = 0
         If highsalesprice = "-" Then highsalesprice = 0
         If lowrentprice = "-" Then lowrentprice = 0
         If midrentprice = "-" Then midrentprice = 0
         If highrentprice = "-" Then highrentprice = 0
         stories = "최저 " & stories2 & " ~ 최대 " & stories1

         wscript.echo doname & ", " & guname & ", " & dongname & ", " & apartname & ", " & area1 & ", " & area2 & ", " & lowsalesprice & ", " & midsalesprice & ", " & highsalesprice & ", " & lowrentprice & ", " & midrentprice & ", " & highrentprice & ", " & ipjoo & ", " & oldaddress & ", " & newaddress & ", " & sedesoo & ", " & dongsoo & ", " & stories1 & ", " & stories2 & ", " & manageoffice

         sql = "select * from kbsisenew where cido='" & doname & "' and gugun='" & guname & "' and dong='" & dongname & "' and apartname='" & apartname & "' and area1='" & area1 & "'"
         Set Grs = rootdb.execute(sql)
         If Not Grs.EOF Then
            sql = "update kbsisenew set lowsalesprice=" & lowsalesprice & ", midsalesprice=" & midsalesprice & ", highsalesprice=" & highsalesprice & ", lowrentprice=" & lowrentprice & ", midrentprice=" & midrentprice & ", highrentprice=" & highrentprice & ", detailedaddr='" & oldaddress & "', newaddr='" & newaddress & "', sedesoo='" & sedesoo & "', stories='" & stories & "', ipjoo='" & ipjoo & "', dongsoo='" & dongsoo & "', manageoffice='" & manageoffice & "', updated=getdate(), uniqueid='" & uniqueid & "' where cido='" & doname & "' and gugun='" & guname & "' and dong='" & dongname & "' and apartname='" & apartname & "' and area1='" & area1 & "'"
            'wscript.echo sql
            rootdb.execute(sql)
         Else 
            sql = "insert into kbsisenew(cido, gugun, dong, apartname, area1, area2, lowsalesprice, midsalesprice, highsalesprice, lowrentprice, midrentprice, highrentprice, detailedaddr, newaddr, sedesoo, stories, ipjoo, dongsoo, updated, manageoffice, uniqueid) values('" & doname & "', '" & guname & "', '" &  dongname & "', '" &  apartname & "', '" &  area1 & "', '" &  area2 & "', " &  lowsalesprice & ", " & midsalesprice & ", " & highsalesprice & ", " & lowrentprice & ", " & midrentprice & ", " & highrentprice & ", '" & detailedaddr & "', '" & newaddress & "', '" & sedesoo & "', '" & stories & "', '" & ipjoo & "', '" & dongsoo & "', getdate(), '" & manageoffice & "', '" & uniqueid & "')"
            'wscript.echo sql
            rootdb.execute(sql)
         End If
         Grs.close
         wscript.sleep 100
      End If 
   Next
End Function 

Function random(strlen)
  str = "ABCDEFGHIJKLMNOPQSTUVWXYZ0123456789" '랜덤으로 선택된 문자or 숫자
   Randomize '랜덤 초기화
   For randomindex = 1 To strlen
    r = Int((36 - 1 + 1) * Rnd + 1) ' 36은 str의 문자갯수
    serialCode = serialCode + Mid(str,r,1)
   Next 
   random = serialCode
 End Function
