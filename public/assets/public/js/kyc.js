var transactiondata,DemoFinalString= '',sendingdata,dataSendurl;
function captureFinger(url,calldata)
{     var type = calldata.ttfa;
    if(url != '/aeps-verify-finger')
    {
        $("#txtWadh").val('');
    }
    sendingdata = calldata;
    dataSendurl = url;
    $('#txtPidData').val('');
    var SuccessFlag = 0;
        primaryUrl = "http://127.0.0.1:";
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
                httpStaus = true;
                res = { httpStaus: httpStaus, data: data };

                finalUrl = primaryUrl + i.toString();

                var $doc = data;//$.parseXML(data);
                var CmbData1 = $($doc).find('RDService').attr('status');
                var CmbData2 = $($doc).find('RDService').attr('info');

                if (RegExp('\\b' + 'Mantra' + '\\b').test(CmbData2) == true || RegExp('\\b' + 'Morpho_RD_Service' + '\\b').test(CmbData2) == true || RegExp('\\b' + 'SecuGen India Registered device Level 0' + '\\b').test(CmbData2) == true || RegExp('\\b' + 'Precision - Biometric Device is ready for capture' + '\\b').test(CmbData2) == true || RegExp('\\b' + 'RD service for Startek FM220 provided by Access Computech' + '\\b').test(CmbData2) == true || RegExp('\\b' + 'NEXT' + '\\b').test(CmbData2) == true) {
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
                        takeFinferprintr(type);
                        return;
                    }
                    else if (CmbData1 == 'NOTREADY') {
                       /*error('No RD Device are attached or check properly!');*/
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
        error("Connection failed Please try again or device not connected.");
    }
    return res;
}

function takeFinferprintr(type) {
     
    var devicetype = false;
    var run = "mantra";//  $("input[name='capdata']:checked"). val();
    if (run == "iris") {
        var devicetype = true;
        var XML = ' <PidOptions ver="1.0"> <Opts fCount="0" fType="2" iCount="1" pCount="0" format="0" pidVer="2.0" timeout="10000" posh="UNKNOWN" env="P" /> ' + DemoFinalString + '<CustOpts><Param name="mantrakey" value="" /></CustOpts> </PidOptions>';
    } else {
        var devicetype = false;
        if ($("#txtWadh").val().trim() != "") {
            var XML = ' <PidOptions ver="1.0"> <Opts fCount="1" fType="2" iCount="0" pCount="0" format="0" pidVer="2.0" timeout="10000" wadh="' + $("#txtWadh").val() + '" posh="UNKNOWN" env="P" /> ' + DemoFinalString + '<CustOpts><Param name="mantrakey" value="" /></CustOpts> </PidOptions>';
        }
        else {
            var XML = ' <PidOptions ver="1.0"> <Opts fCount="1" fType="2" iCount="0" pCount="0" format="0" pidVer="2.0" timeout="10000" posh="UNKNOWN" env="P" /> ' + DemoFinalString + '<CustOpts><Param name="mantrakey" value="" /></CustOpts> </PidOptions>';
        }
    }
    var finUrl = $('#method').val();
    var verb = "CAPTURE";
    var err = "";
    var res;
    $.support.cors = true;
    var httpStaus = false;
    var baseURL = $('head base').attr('href');
    var jsonstr = "";
     $("#staticBackdrop").modal("hide");
    $.ajax({
        type: "CAPTURE",
        async: false,
        crossDomain: true,
        url: finUrl,
        data: XML,
        contentType: "text/xml; charset=utf-8",
        processData: false,
        success: function (data) {
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
            
         
            
                // CallApi(xmlString,siteUrl+'/aeps-verify-finger',run);
            /*console.log(xmlString);
            return;*/
            httpStaus = true;
            $("#ddlAVDM").val(xmlString);
            httpStaus = true;
                    res = { httpStaus: httpStaus, data: data };

                    var $doc = xmlString;
                        $("#divMsg").append("<div> - Capture : <img style='width:25px' src='true.png'/></div>");
                        $(".overlay").hide();
                        $("#divDvc").css("display", "none");
                        var errorcode = $($doc).find("Resp").attr("errCode");
                        var errorinfo = $($doc).find("Resp").attr("errInfo");
                        var fcount = $($doc).find("Resp").attr("fCount");
                        var ftype = $($doc).find("Resp").attr("fType");
                        var nmpoints = $($doc).find("Resp").attr("nmPoints");
                        var qscore = $($doc).find("Resp").attr("qScore");
                        
                        var dpid = $($doc).find("DeviceInfo").attr("dpId");
                        var rdsid = $($doc).find("DeviceInfo").attr("rdsId");
                        var rdsver = $($doc).find("DeviceInfo").attr("rdsVer");
                        var mi = $($doc).find("DeviceInfo").attr("mi");
                        var mc = $($doc).find("DeviceInfo").attr("mc");
                        var dc = $($doc).find("DeviceInfo").attr("dc");
                        
                        var dsrno = $($doc).find('[name="srno"]').attr('value');
                        var ci = $($doc).find("Skey").attr("ci");
                        var sessionkey = $($doc).find("Skey").text().trim();
                        var hmac = $($doc).find("Hmac").text().trim();
                        
                        var pidtype = $($doc).find('Data').attr('type');
                        var piddata = $($doc).find('Data').text();
                        var dataObject = {
                              'errorCode': errorcode,
                              'errorInfo': 'Success',
                              'fCount': fcount,
                              'fType': ftype,
                              'nmPoints': nmpoints,
                              'qScore': qscore,
                              'dpId': dpid,
                              'rdsId': rdsid,
                              'rdsVer': rdsver,
                              'mi': mi,
                              'mc': mc,
                              'dc': dc,
                              'dsrno': dsrno,
                              'ci': ci,
                              'sessionKey': sessionkey,
                              'hmac': hmac,
                              'pidType': pidtype,
                              'pidData': piddata,
                              'newFingerData' : xmlString,
                            };
                            dataObject = { ...dataObject, ...sendingdata };
                             $("#staticBackdrop").modal("show");
                            // dataObject.tdata=sendingdata;
                            $.ajax({
                                url : dataSendurl,
                                method : 'post',
                                data : dataObject,
                                success:function(data)
                                {
                                     console.log(data)
                                    $("#staticBackdrop").modal("hide");
                                    if(data.status == 'SUCCESS')
                                    {
                                        if(data.type == 'kyc')
                                        {
                                            if (data.isFor == 'transaction') {
                                                    if (type == 'ttfa') {
                                                        transactionTTFA(data);
                                                    } else if (type == 'atfa') {
                                                        transactionTFA(data);
                                                    }else{
                                                 successReload(data.message);
                                                    }
                                                } else {    
                                                    successReload(data.message);
                                                }
                                                
                                            // if(data.isFor == 'transaction')
                                            // transactionTFA(data);
                                            // else
                                            // successReload(data.message);
                                        }
                                        else
                                        {
                                            $("#aeps-receipt-modal").modal('show');
                                            $("#aeps-receipt-data").html(data.view);
                                        }
                                    }
                                    else if(data.status == 'INFO')
                                    {
                                        info(data.message);
                                    }
                                    else
                                    {
                                        error(data.message);
                                    }
                                }
                            })
            
            
            
        },
    });
}