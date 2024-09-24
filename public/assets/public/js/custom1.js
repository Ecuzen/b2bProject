var siteUrl=window.location.origin;
function parseXmlToJson(xml) {
    const json = {};
    for (const res of xml.matchAll(/(?:<(\w*)(?:\s[^>]*)*>)((?:(?!<\1).)*)(?:<\/\1>)|<(\w*)(?:\s*)*\/>/gm)) {
        const key = res[1] || res[3];
        const value = res[2] && parseXmlToJson(res[2]);
        json[key] = ((value && Object.keys(value).length) ? value : res[2]) || null;

    }
    return json;
}

   
 function CallkycCapture(path,token) {
           discoverAvdm1(path);
}
 var GetPIString = '';
        var GetPAString = '';
        var GetPFAString = '';
        var DemoFinalString = '';
        var select = '';
        select += '<option val=0>Select</option>';
        for (i = 1; i <= 100; i++) {
            select += '<option val=' + i + '>' + i + '</option>';
        }
        $('#drpMatchValuePI').html(select);
        $('#drpMatchValuePFA').html(select);
        $('#drpLocalMatchValue').html(select);
        $('#drpLocalMatchValuePI').html(select);
        var finalUrl = "";
        var MethodInfo = "";
        var MethodCapture = "";
        //var primaryUrl = "https://127.0.0.1:";// For HTTPS
        var primaryUrl = "http://127.0.0.1:";// For HTTP
        
        function reset() {
            $('#txtWadh').val('');
            $('#txtPidData').val('');
        }

function discoverAvdm1(transactiondata) {
    /*alert('hii');*/
    openNav();
    $('#txtWadh').val('');
    $('#txtPidData').val('');
    var SuccessFlag = 0;
    try {
        var protocol = window.location.href;
        if ((protocol.indexOf("https") >= 0) && ((transactiondata.response.capture) != "nextrd")) {
            primaryUrl = "http://127.0.0.1:";
        } else if ((transactiondata.response.capture) == "nextrd") {
            primaryUrl = "http://127.0.0.1:";
        } else {
            primaryUrl = "http://127.0.0.1:";
        }
    } catch (e)
    { }

    url = "";

    for (var i = 11100; i <= 11120; i++) {
        $("#lblstatus").text("Discovering RD service on port : " + i.toString());
        var verb = "RDSERVICE";
        var err = "";
        SuccessFlag = 0;
        var res;
        $.support.cors = true;
        var httpStaus = false;
        var jsonstr = "";
        var data = new Object();
        var obj = new Object();
        $.ajax({
            type: "RDSERVICE",
            async: false,
            crossDomain: true,
            dataType: "xml",
            url: primaryUrl + i.toString(),
            contentType: "text/xml; charset=\"utf-8\"",
            processData: false,
            cache: false,

            success: function (data) {
               /* debugger;*/

                httpStaus = true;
                res = { httpStaus: httpStaus, data: data };

                finalUrl = primaryUrl + i.toString();

                var $doc = data;//$.parseXML(data);
                var CmbData1 = $($doc).find('RDService').attr('status');
                var CmbData2 = $($doc).find('RDService').attr('info');

                if (RegExp('\\b' + 'Mantra' + '\\b').test(CmbData2) == true || RegExp('\\b' + 'Morpho_RD_Service' + '\\b').test(CmbData2) == true || RegExp('\\b' + 'SecuGen India Registered device Level 0' + '\\b').test(CmbData2) == true || RegExp('\\b' + 'Precision - Biometric Device is ready for capture' + '\\b').test(CmbData2) == true || RegExp('\\b' + 'RD service for Startek FM220 provided by Access Computech' + '\\b').test(CmbData2) == true || RegExp('\\b' + 'NEXT' + '\\b').test(CmbData2) == true) {
                    closeNav();
                    /*console.log($($doc).find('Interface').eq(0).attr('path'));*/

                    if (RegExp('\\b' + 'Mantra' + '\\b').test(CmbData2) == true) {

                        if ($($doc).find('Interface').eq(0).attr('path') == "/rd/capture") {
                            MethodCapture = $($doc).find('Interface').eq(0).attr('path');
                        }
                        if ($($doc).find('Interface').eq(1).attr('path') == "/rd/capture") {
                            MethodCapture = $($doc).find('Interface').eq(1).attr('path');
                        }
                        if ($($doc).find('Interface').eq(0).attr('path') == "/rd/info") {
                            MethodInfo = $($doc).find('Interface').eq(0).attr('path');
                        }
                        if ($($doc).find('Interface').eq(1).attr('path') == "/rd/info") {
                            MethodInfo = $($doc).find('Interface').eq(1).attr('path');
                        }
                        $("#hdnDevice").val('mantra');
                        var usernamett = $("#hdnDevice").val();
                        
                    } else if (RegExp('\\b' + 'Morpho_RD_Service' + '\\b').test(CmbData2) == true) {
                        MethodCapture = $($doc).find('Interface').eq(0).attr('path');
                        MethodInfo = $($doc).find('Interface').eq(1).attr('path');
                        $("#hdnDevice").val('morpho');
                        var usernamett = $("#hdnDevice").val();
                        
                    } else if (RegExp('\\b' + 'SecuGen India Registered device Level 0' + '\\b').test(CmbData2) == true) {
                        MethodCapture = $($doc).find('Interface').eq(0).attr('path');
                        MethodInfo = $($doc).find('Interface').eq(1).attr('path');
                        $("#hdnDevice").val('secugen');
                    } else if (RegExp('\\b' + 'Precision - Biometric Device is ready for capture' + '\\b').test(CmbData2) == true) {
                        MethodCapture = $($doc).find('Interface').eq(0).attr('path');
                        MethodInfo = $($doc).find('Interface').eq(1).attr('path');
                        $("#hdnDevice").val('precision');
                    } else if (RegExp('\\b' + 'RD service for Startek FM220 provided by Access Computech' + '\\b').test(CmbData2) == true) {
                        MethodCapture = $($doc).find('Interface').eq(0).attr('path');
                        MethodInfo = $($doc).find('Interface').eq(1).attr('path');
                        $("#hdnDevice").val('startek');
                        var usernamett = $("#hdnDevice").val();
                        
                    } else if (RegExp('\\b' + 'NEXT' + '\\b').test(CmbData2) == true) {
                        MethodCapture = $($doc).find('Interface').eq(0).attr('path');
                        MethodInfo = $($doc).find('Interface').eq(1).attr('path');
                        $("#hdnDevice").val('nextrd');
                    }
                    
                    if (CmbData1 == 'READY') {
                        SuccessFlag = 1;

                        $('#method').val(finalUrl + MethodCapture);
                        $('#datasubmit').prop('disabled', false);
                        $('#btnBalance').prop('disabled', false);
                        console.log(transactiondata);
                        BalanceEnquiry(transactiondata);
                        return;
                    }
                    else if (CmbData1 == 'NOTREADY') {
                       /* alert("Device Not Discover");*/
                        return false;
                    }
                }
            },
            error: function (jqXHR, ajaxOptions, thrownError) {
                //res = { httpStaus: httpStaus, err: getHttpError(jqXHR) };
            },
        });

        if (SuccessFlag == 1) {
            break;
        }
    }
    if (SuccessFlag == 0) {
        alert("Connection failed Please try again or device not connected.");
    }
    closeNav();
    return res;
}

function openNav() {
    //document.getElementById("myNav").style.width = "100%";
    //$("#myNav").css('width', '100%');
}

function closeNav() {
    //document.getElementById("myNav").style.width = "0%";
    $("#myNav").css('width', '0%');
}

function capturealert() {
    CaptureAvdm();
}







function BalanceEnquiry(transactiondata) {
    var devicetype = false;
    var run = "mantra";//  $("input[name='capdata']:checked"). val();
    if (run == "iris") {
        var devicetype = true;
        var XML = ' <PidOptions ver="1.0"> <Opts fCount="0" fType="2" iCount="1" pCount="0" format="0" pidVer="2.0" timeout="10000" posh="UNKNOWN" env="P" /> ' + DemoFinalString + '<CustOpts><Param name="mantrakey" value="" /></CustOpts> </PidOptions>';
    } else {

        var devicetype = false;
        // debugger;
        if ($("#txtWadh").val().trim() != "") {
            var XML = ' <PidOptions ver="1.0"> <Opts fCount="1" fType="2" iCount="0" pCount="0" format="0" pidVer="2.0" timeout="10000" wadh="' + $("#txtWadh").val() + '" posh="UNKNOWN" env="P" /> ' + DemoFinalString + '<CustOpts><Param name="mantrakey" value="" /></CustOpts> </PidOptions>';
        }
        else {
            var XML = ' <PidOptions ver="1.0"> <Opts fCount="1" fType="2" iCount="0" pCount="0" format="0" pidVer="2.0" timeout="10000" posh="UNKNOWN" env="P" /> ' + DemoFinalString + '<CustOpts><Param name="mantrakey" value="" /></CustOpts> </PidOptions>';
        }
    }
    
    //$('#loaderbala').css("display", "block");
    var finUrl = $('#method').val();
    //alert(finUrl);
    var verb = "CAPTURE";
    var err = "";
    var res;
    $.support.cors = true;
    var httpStaus = false;
    var baseURL = $('head base').attr('href');
    var jsonstr = "";
    $.ajax({
        type: "CAPTURE",
        async: false,
        crossDomain: true,
        url: finUrl,
        data: XML,
        contentType: "text/xml; charset=utf-8",
        processData: false,
        success: function (data) {
            console.log(data);
            // debugger;
            run = $("#hdnDevice").val();
            if (run == "morpho") {
                var xmlString = (new XMLSerializer()).serializeToString(data);  //morpho
            } else if (run == "mantra") {
                var xmlString = data;  //mantra
            } else if (run == "secugen") {
                var xmlString = (new XMLSerializer()).serializeToString(data);  //secugen
            } else if (run == "precision") {
                var xmlString = (new XMLSerializer()).serializeToString(data);
            } else if (run == "startek") {
                var xmlString = (new XMLSerializer()).serializeToString(data);  //startek
            } else if (run == "nextrd") {
                var xmlString = (new XMLSerializer()).serializeToString(data);  //next rd
            } else if (run == "iris") {
                var xmlString = data;   //MANTRA IRIS
            }
            
         
            
                CallApi(xmlString,siteUrl+'/aeps-verify-finger',run);
           /* console.log(xmlString);*/
            httpStaus = true;
            $("#ddlAVDM").val(xmlString);
            
            
            
        },
    });
}

function discoverAvdm(url) {

var SuccessFlag = 0;
if(url==undefined){
    url="http://127.0.0.1:11100";
}

var verb = "RDSERVICE";
var err = "";
SuccessFlag = 0;
var res;
$.support.cors = true;
var httpStaus = false;
var jsonstr = "";
var data = new Object();
var obj = new Object();

$.ajax({

    type: "RDSERVICE",
    async: false,
    crossDomain: true,
    url: url,
    contentType: "text/xml; charset=utf-8",
    processData: false,
    cache: false,
    crossDomain: true,

    success: function(data) {
        var xm=parseXmlToJson(data);
/*console.log(xm);*/
        httpStaus = true;
        res = {
            httpStaus: httpStaus,
            data: data
        };
        //alert(data);
        finalUrl = url;
        var $doc = $.parseXML(data);
        var CmbData1 = $($doc).find('RDService').attr('status');
        var CmbData2 = $($doc).find('RDService').attr('info');
        MethodCapture = "/rd/capture";
        MethodInfo = "/rd/info";

        SuccessFlag = 1;
        return;

    },
    error: function(jqXHR, ajaxOptions, thrownError) {
        if (i == "8005" && OldPort == true) {
            OldPort = false;
            i = "11099";
        }

    },

});









if (SuccessFlag == 0) {
// alert("Connection failed Please try again.");
ClearCtrl();
 discoverAvdm('https://127.0.0.1:8005');
}




return res;
}































//captcture mantra

function CallApi(BiometricData,path,device) {
    
    alert(BiometricData);
    return;
 
 var device  = sessionStorage.getItem("device");

var errCode = $(BiometricData).find('Resp').eq(0).attr('errCode');

if(device == "MANTRA")
{
var errInfo = $(BiometricData).find('Resp').eq(0).attr('errInfo');
}else{
var errInfo = "Success";

}

var fCount = $(BiometricData).find('Resp').eq(0).attr('fCount');
var fType = $(BiometricData).find('Resp').eq(0).attr('fType');
var nmPoints = $(BiometricData).find('Resp').eq(0).attr('nmPoints');
var qScore = $(BiometricData).find('Resp').eq(0).attr('qScore');
// responce end
//----------------------------------------------------//
//Device Info
var dpId = $(BiometricData).find('DeviceInfo').eq(0).attr('dpId');
var rdsId = $(BiometricData).find('DeviceInfo').eq(0).attr('rdsId');
var rdsVer = $(BiometricData).find('DeviceInfo').eq(0).attr('rdsVer');
var mi = $(BiometricData).find('DeviceInfo').eq(0).attr('mi');
var mc = $(BiometricData).find('DeviceInfo').eq(0).attr('mc');
var dc = $(BiometricData).find('DeviceInfo').eq(0).attr('dc');
// Device INFO END
//--------------------------------------------------//
// additional info
var srno = $(BiometricData).find('Param').eq(0).attr('value');


if(device == "MANTRA")
{
  var sysid = $(BiometricData).find('Param').eq(1).attr('value');
  var ts = $(BiometricData).find('Param').eq(2).attr('value');
}else{
 
  var sysid = Math.random().toString(13).replace('0.', '');
  var ts = Date.now();
}


//$(BiometricData).find('Param').eq(2).attr('value');
//addtional info end
//-------------------------------------------------//
//session info
var ci = $(BiometricData).find('Skey').eq(0).attr('ci');
$skey = $(BiometricData).find('Skey');
var skey = $skey.text();
//session info end
//--------------------------------------------------//
//hmac
$hmac = $(BiometricData).find('Hmac');
var hmac = $hmac.text();
//hmac end
//-------------------------------------------------//
var datatype = $(BiometricData).find('Data').eq(0).attr('type');
$data = $(BiometricData).find('Data');
var pdata = $data.text();


var trfdata = {
 "errCode" : errCode,
 "errInfo" : errInfo,
 "fCount" : fCount,
 "fType" : fType,
 "nmPoints" : nmPoints,
 "qScore" : qScore,
 "dpId" : dpId,
 "rdsId" : rdsId,
 "rdsVer" : rdsVer,
 "mi" : mi,
 "mc" : mc,
 "dc" : dc,
 "srno" : srno,
 "sysid" : sysid,
 "ts" : ts,
 "ci" : ci,
 "skey" : skey,
 "hmac" : hmac,
 "datatype" : datatype,
 "pdata" : pdata,
 '_token' : '{{csrf_token()}}'
};
    
    
    

if(path == "BE")
{
var phone = $("#phone").val();
var adhaar = $("#adhaar").val();
var bank = $("#bank").val();

var name="NA";
trfdata.name = name;
trfdata.adhaar = adhaar;
trfdata.bank = bank;
trfdata.phone = phone;
trfdata.pin=$('#pin').val();
$('#pin').val('');


$.post(siteUrl+"/iciciaeps/be",trfdata,function( data ) {
  
    
    document.getElementById("bebtn").disabled = false;
    
    document.getElementById("bebtn").innerHTML = "CHECK BALANCE";


       $("#status").html(data);
       $("#success").modal("show"); 
    



});



}




if(path == "MS")
{
    
    
var phone = $("#phonem").val();
var adhaar = $("#adhaarm").val();
var bank = $("#bankm").val();
var name=$('#name').val();
trfdata.name = name;
trfdata.adhaar = adhaar;
trfdata.bank = bank;
trfdata.phone = phone; 
trfdata.pin=$('#pin').val();
$('#pin').val('');

 $.post( siteUrl+"/iciciaeps/msi",trfdata,function(data) {

  document.getElementById("msbtn").disabled = false;
  document.getElementById("msbtn").innerHTML = "GET MINI STATEMENT";

    
       $("#status").html(data);
$("#success").modal("show"); 
    


});




}


if(path == "CW")
{

 var phone = $("#phonec").val();
        var adhaar = $("#adhaarc").val();
        var bank = $("#bankc").val();
var amount = $("#amountc").val();
var name=$('#name').val();
trfdata.name = name;
trfdata.adhaar = adhaar;
trfdata.bank = bank;
trfdata.phone = phone; 
trfdata.amount = amount;
trfdata.pin=$('#pin').val();
$('#pin').val('');
 $.post( siteUrl+"/iciciaeps/cw",trfdata,function(data) {

  document.getElementById("cwbtn").disabled = false;
  document.getElementById("cwbtn").innerHTML = "CASH WITHDRAWAL";

    $("#status").html(data);
$("#success").modal("show"); 


});




}


if(path == "BIOM")
{
trfdata.name = 'name';
trfdata.adhaar = 'adhaar';
trfdata.bank = 'bank';
trfdata.phone = 'phone'; 
trfdata.amount = 'amount';
trfdata.pin=$('#pin').val();
$('#pin').val('');
 $.post(url,trfdata,function(data) {

//   document.getElementById("apbtn").disabled = false;
//   document.getElementById("apbtn").innerHTML = "ADHAAR PAY";
res=JSON.parse(data);
    console.log(res);
    if(res.status=="SUCCESS"){
    //   $("#txnform").html(res.data);
// $("#success").modal("show"); 
    }else{
    //     $('#msgtitle').html('<h5 style="color:#691963">Could not initiate the transaction</h5>');
    // $('#msg').html(`<b><center style="color:white;">${res.msg}</center>`);
    // $('#success').modal('show');
    }


});




}
if(path == "BIOM_LOGIN")
{

$('#pin').val('');
trfdata.username=$('#username').val();
trfdata.txtCaptcha=$('#txtCaptcha').val();
trfdata.auth=$('#auth').val();
trfdata.method="biom";
 $.post(url,trfdata,function(data) {

});
}
if(path == "AP")
{

var phone = $("#phonea").val();
        var adhaar = $("#adhaara").val();
        var bank = $("#banka").val();
var amount = $("#amounta").val();
var name=$('#name').val();
trfdata.name = name;
trfdata.adhaar = adhaar;
trfdata.bank = bank;
trfdata.phone = phone; 
trfdata.amount = amount;
trfdata.pin=$('#pin').val();
$('#pin').val('');
 $.post( siteUrl+"/iciciaeps/ap",trfdata,function(data) {

  document.getElementById("apbtn").disabled = false;
  document.getElementById("apbtn").innerHTML = "ADHAAR PAY";
$("#status").html(data);
$("#success").modal("show"); 
});
}

if(path == "DK")
{
    
//alert('custom');

$.post( siteUrl+"/aeps/kycfingerverify",trfdata,function( data ) {
    
  document.getElementById("kycbtn").disabled = false;
  document.getElementById("kycbtn").innerHTML = "Capture Finger";
if(data == "1"){
    alert('Kyc Successfull');
    location.href="/aeps";
}else{
    alert(data);
}
});
}
}

//captcture mantra
function CaptureMorpho(path,url) {
    
    var url = url+"/capture";
    var PIDOPTS = '<PidOptions ver=\"1.0\">' + '<Opts fCount=\"1\" fType=\"2\" iCount=\"\" iType=\"\" pCount=\"\" pType=\"\" format=\"0\" pidVer=\"2.0\" timeout=\"10000\"  env=\"P\" otp=\"\" wadh=\"\" posh=\"\"/>' + '</PidOptions>';
    /*
    format=\"0\"     --> XML
    format=\"1\"     --> Protobuf   env=\"P\"
    */
    var xhr;
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer, return version number
    {
        //IE browser
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }
    else {
        //other browser
        xhr = new XMLHttpRequest();
    }

    xhr.open('CAPTURE', url, true);
    xhr.setRequestHeader("Content-Type", "text/xml");
    xhr.setRequestHeader("Accept", "text/xml");

    xhr.onload = function () {
        //if(xhr.readyState == 1 && count == 0){
        //	fakeCall();
        //}
        if (xhr.readyState == 4) {
            var status = xhr.status;

            if (status == 200) {
              
              
                 
                    CallApi(xhr.responseText,path);
               

        }
        else {
            alert(xhr.response);

        }
    }

    };

xhr.send(PIDOPTS);

}

function ClearCtrl()
{
// alert("Clear Control");

}
