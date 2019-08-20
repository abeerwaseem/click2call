<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Click2Call</title>

            <!-- SIPML5 API:
        DEBUG VERSION: 'SIPml-api.js'
        RELEASE VERSION: 'release/SIPml-api.js'
        -->
        <script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
        <script src="calling/SIPml-api.js?svn=252" type="text/javascript"> </script>

        <!-- Styles -->
        <link href="calling/assets/css/bootstrap.css" rel="stylesheet" />
        <style type="text/css">
            body {
                padding-top: 80px;
                padding-bottom: 40px;
            }

            .navbar-inner-red {
                background-color: #600000;
                background-image: none;
                background-repeat: no-repeat;
                filter: none;
            }

            .full-screen {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
            }

            .normal-screen {
                position: relative;
            }

            .call-options {
                padding: 5px;
                background-color: #f0f0f0;
                border: 1px solid #eee;
                border: 1px solid rgba(0, 0, 0, 0.08);
                -webkit-border-radius: 4px;
                -moz-border-radius: 4px;
                border-radius: 4px;
                -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
                -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
                box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
                -webkit-transition-property: opacity;
                -moz-transition-property: opacity;
                -o-transition-property: opacity;
                -webkit-transition-duration: 2s;
                -moz-transition-duration: 2s;
                -o-transition-duration: 2s;
            }

            .tab-video,
            .div-video {
                width: 100%;
                height: 0px;
                -webkit-transition-property: height;
                -moz-transition-property: height;
                -o-transition-property: height;
                -webkit-transition-duration: 2s;
                -moz-transition-duration: 2s;
                -o-transition-duration: 2s;
            }

            .label-align {
                display: block;
                padding-left: 15px;
                text-indent: -15px;
            }

            .input-align {
                width: 13px;
                height: 13px;
                padding: 0;
                margin: 0;
                vertical-align: bottom;
                position: relative;
                top: -1px;
                *overflow: hidden;
            }

            .glass-panel {
                z-index: 99;
                position: fixed;
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
                top: 0;
                left: 0;
                opacity: 0.8;
                background-color: Gray;
            }

            .div-keypad {
                z-index: 100;
                position: fixed;
                -moz-transition-property: left top;
                -o-transition-property: left top;
                -webkit-transition-duration: 2s;
                -moz-transition-duration: 2s;
                -o-transition-duration: 2s;
            }

            .previewvideo {
                position: absolute;
                width: 88px;
                height: 72px;
                margin-top: -42px;
            }
        </style>
        <link href="calling/assets/css/bootstrap-responsive.css" rel="stylesheet" />
        <!-- Le fav and touch icons -->
        <link rel="shortcut icon" href="calling/assets/ico/favicon.ico" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="calling/assets/ico/apple-touch-icon-114-precomposed.png" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="calling/assets/ico/apple-touch-icon-72-precomposed.png" />
        <link rel="apple-touch-icon-precomposed" href="calling/assets/ico/apple-touch-icon-57-precomposed.png" />

    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <label style="width: 100%;" align="center" id="txtCallStatus"></label>
            <a href="#" id="btnCall" class="btn btn-primary" onclick='callStack();'>call</a>
            <a href="#" id="btnCall" class="btn btn-primary" onclick='sipHangUp();'>Disconnect</a>
        </div>
        <!-- KeyPad Div -->
    

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script type="text/javascript" src="calling/assets/js/jquery.js"></script>
        <script type="text/javascript" src="calling/assets/js/bootstrap-transition.js"></script>
        <script type="text/javascript" src="calling/assets/js/bootstrap-alert.js"></script>
        <script type="text/javascript" src="calling/assets/js/bootstrap-modal.js"></script>
        <script type="text/javascript" src="calling/assets/js/bootstrap-dropdown.js"></script>
        <script type="text/javascript" src="calling/assets/js/bootstrap-scrollspy.js"></script>
        <script type="text/javascript" src="calling/assets/js/bootstrap-tab.js"></script>
        <script type="text/javascript" src="calling/assets/js/bootstrap-tooltip.js"></script>
        <script type="text/javascript" src="calling/assets/js/bootstrap-popover.js"></script>
        <script type="text/javascript" src="calling/assets/js/bootstrap-button.js"></script>
        <script type="text/javascript" src="calling/assets/js/bootstrap-collapse.js"></script>
        <script type="text/javascript" src="calling/assets/js/bootstrap-carousel.js"></script>
        <script type="text/javascript" src="calling/assets/js/bootstrap-typeahead.js"></script>

        <!-- Audios -->
        <audio id="audio_remote" autoplay="autoplay"> </audio>
        <audio id="ringtone" loop src="calling/sounds/ringtone.wav"> </audio>
        <audio id="ringbacktone" loop src="calling/sounds/ringbacktone.wav"> </audio>
        <audio id="dtmfTone" src="calling/sounds/dtmf.wav"> </audio>

        <!-- Javascript code -->
        <script type="text/javascript">
            
            var sipStartStack = null;

            function callStack(){
                
                checkData(function(sipp){
                    var readyCallback = function(u,p,a){
                        createSipStack(u,p,a); // see next section
                    };
                    var errorCallback = function(e){
                        console.error('Failed to initialize the engine: ' + e.message);
                    }

                    SIPml.init(readyCallback(sipp.sip_username,sipp.sip_password,sipp.sip_address), errorCallback);
                    var sipStack;
                    var eventsListener = function(e){
                        if(e.type == 'started'){
                            login();
                        }
                        else if(e.type == 'i_new_message'){ // incoming new SIP MESSAGE (SMS-like)
                            acceptMessage(e);
                        }
                        else if(e.type == 'i_new_call'){ // incoming audio/video call
                            acceptCall(e);
                        }
                    }
                    
                    function createSipStack(u,p,a){
                        sipStack = new SIPml.Stack({
                                realm: a,
                                impi: u,
                                impu: 'sip:'+ u +'@'+ a,
                                password: p,
                                display_name: 'Dev Test Pk',
                                websocket_proxy_url: 'wss://calling.hexzap.com:10062',
                                outbound_proxy_url: 'udp://proxy.sipthor.net:5060',
                                ice_servers: [{
                                    urls: 'turn:numb.viagenie.ca',
                                    credential: 'muazkh',
                                    username: 'webrtc@live.com'
                                },{urls:'stun:stun4.l.google.com:19302'}],
                                enable_rtcweb_breaker: true,
                                events_listener: { events: '*', listener: onSipEventStack },
                                enable_early_ims: true, // Must be true unless you're using a real IMS network
                                enable_media_stream_cache: true,
                                bandwidth: null, // could be redefined a session-level
                                video_size: null, // could be redefined a session-level
                                sip_headers: [
                                        { name: 'User-Agent', value: 'IM-client/OMA1.0 sipML5-v1.2016.03.04' },
                                        { name: 'Organization', value: 'Doubango Telecom' }
                                ]
                            }
                        );
                    }
                    //console.log("abeer", sipStack);
                    sipStack.start();

                    setTimeout(function(){ makeCall(); }, 1000);
                    
                    // Callback function for SIP Stacks
                    function onSipEventStack(e /*SIPml.Stack.Event*/) {
                        tsk_utils_log_info('==stack event = ' + e.type);
                        switch (e.type) {
                            case 'started':
                                {
                                    // catch exception for IE (DOM not ready)
                                    try {

                                        // LogIn (REGISTER) as soon as the stack finish starting
                                        oSipSessionRegister = this.newSession('register', {
                                            expires: 200,
                                            events_listener: { events: '*', listener: onSipEventSession },
                                            sip_caps: [
                                                        { name: '+g.oma.sip-im', value: null },
                                                        //{ name: '+sip.ice' }, // rfc5768: FIXME doesn't work with Polycom TelePresence
                                                        { name: '+audio', value: null },
                                                        { name: 'language', value: '\"en,fr\"' }
                                            ]
                                        });
                                        oSipSessionRegister.register();
                                    }
                                    catch (e) {
                                        // txtRegStatus.value = txtRegStatus.innerHTML = "<b>1:" + e + "</b>";
                                        // btnRegister.disabled = false;
                                        console.log(e);
                                    }
                                    break;
                                }
                            case 'stopping': case 'stopped': case 'failed_to_start': case 'failed_to_stop':
                                {
                                    var bFailure = (e.type == 'failed_to_start') || (e.type == 'failed_to_stop');
                                    oSipStack = null;
                                    oSipSessionRegister = null;
                                    oSipSessionCall = null;

                                    //uiOnConnectionEvent(false, false);

                                    stopRingbackTone();
                                    stopRingTone();

                                    // uiVideoDisplayShowHide(false);
                                    // divCallOptions.style.opacity = 0;

                                    // txtCallStatus.innerHTML = '';
                                    // txtRegStatus.innerHTML = bFailure ? "<i>Disconnected: <b>" + e.description + "</b></i>" : "<i>Disconnected</i>";
                                    console.log(e.description);
                                    break;
                                }

                            case 'i_new_call':
                                {
                                    if (callSession) {
                                        // do not accept the incoming call if we're already 'in call'
                                        e.newSession.hangup(); // comment this line for multi-line support
                                    }
                                    else {
                                        callSession = e.newSession;
                                        // start listening for events
                                        callSession.setConfiguration(oConfigCall);

                                        uiBtnCallSetText('Answer');
                                        btnHangUp.value = 'Reject';
                                        btnCall.disabled = false;
                                        btnHangUp.disabled = false;

                                        startRingTone();

                                        var sRemoteNumber = (oSipSessionCall.getRemoteFriendlyName() || 'unknown');
                                        txtCallStatus.innerHTML = "<i>Incoming call from [<b>" + sRemoteNumber + "</b>]</i>";
                                        showNotifICall(sRemoteNumber);
                                    }
                                    break;
                                }

                            case 'm_permission_requested':
                                {
                                    //divGlassPanel.style.visibility = 'visible';
                                    break;
                                }
                            case 'm_permission_accepted':
                            case 'm_permission_refused':
                                {
                                    //divGlassPanel.style.visibility = 'hidden';
                                    if (e.type == 'm_permission_refused') {
                                        //uiCallTerminated('Media stream permission denied');
                                    }
                                    break;
                                }

                            case 'starting': default: break;
                        }
                    };

                    // Callback function for SIP sessions (INVITE, REGISTER, MESSAGE...)
                    function onSipEventSession(e /* SIPml.Session.Event */) {
                        tsk_utils_log_info('==session event = ' + e.type);

                        switch (e.type) {
                            case 'connecting': case 'connected':
                                {
                                    var bConnected = (e.type == 'connected');
                                    if (e.session == oSipSessionRegister) {
                                        //uiOnConnectionEvent(bConnected, !bConnected);
                                        //txtRegStatus.innerHTML = "<i>" + e.description + "</i>";
                                    }
                                    else if (e.session == callSession) {
                                        btnHangUp.value = 'HangUp';
                                        btnCall.disabled = true;
                                        btnHangUp.disabled = false;
                                        btnTransfer.disabled = false;
                                        if (window.btnBFCP) window.btnBFCP.disabled = false;

                                        if (bConnected) {
                                            stopRingbackTone();
                                            stopRingTone();

                                            if (oNotifICall) {
                                                oNotifICall.cancel();
                                                oNotifICall = null;
                                            }
                                        }

                                        txtCallStatus.innerHTML = "<i>" + e.description + "</i>";
                                        divCallOptions.style.opacity = bConnected ? 1 : 0;

                                        if (SIPml.isWebRtc4AllSupported()) { // IE don't provide stream callback
                                            uiVideoDisplayEvent(false, true);
                                            uiVideoDisplayEvent(true, true);
                                        }
                                    }
                                    break;
                                } // 'connecting' | 'connected'
                            case 'terminating': case 'terminated':
                                {
                                    if (e.session == oSipSessionRegister) {
                                        uiOnConnectionEvent(false, false);

                                        oSipSessionCall = null;
                                        oSipSessionRegister = null;

                                        txtRegStatus.innerHTML = "<i>" + e.description + "</i>";
                                    }
                                    else if (e.session == callSession) {
                                        //uiCallTerminated(e.description);
                                        console.log(e.description);
                                    }
                                    break;
                                } // 'terminating' | 'terminated'

                            case 'm_stream_video_local_added':
                                {
                                    if (e.session == callSession) {
                                        uiVideoDisplayEvent(true, true);
                                    }
                                    break;
                                }
                            case 'm_stream_video_local_removed':
                                {
                                    if (e.session == callSession) {
                                        uiVideoDisplayEvent(true, false);
                                    }
                                    break;
                                }
                            case 'm_stream_video_remote_added':
                                {
                                    if (e.session == callSession) {
                                        uiVideoDisplayEvent(false, true);
                                    }
                                    break;
                                }
                            case 'm_stream_video_remote_removed':
                                {
                                    if (e.session == callSession) {
                                        uiVideoDisplayEvent(false, false);
                                    }
                                    break;
                                }

                            case 'm_stream_audio_local_added':
                            case 'm_stream_audio_local_removed':
                            case 'm_stream_audio_remote_added':
                            case 'm_stream_audio_remote_removed':
                                {
                                    break;
                                }

                            case 'i_ect_new_call':
                                {
                                    oSipSessionTransferCall = e.session;
                                    break;
                                }

                            case 'i_ao_request':
                                {
                                    if (e.session == callSession) {
                                        var iSipResponseCode = e.getSipResponseCode();
                                        if (iSipResponseCode == 180 || iSipResponseCode == 183) {
                                            startRingbackTone();
                                            txtCallStatus.innerHTML = '<i>Remote ringing...</i>';
                                        }
                                    }
                                    break;
                                }

                            case 'm_early_media':
                                {
                                    if (e.session == callSession) {
                                        stopRingbackTone();
                                        stopRingTone();
                                        txtCallStatus.innerHTML = '<i>Early media started</i>';
                                    }
                                    break;
                                }

                            case 'm_local_hold_ok':
                                {
                                    if (e.session == callSession) {
                                        if (callSession.bTransfering) {
                                            callSession.bTransfering = false;
                                            // this.AVSession.TransferCall(this.transferUri);
                                        }
                                        btnHoldResume.value = 'Resume';
                                        btnHoldResume.disabled = false;
                                        txtCallStatus.innerHTML = '<i>Call placed on hold</i>';
                                        callSession.bHeld = true;
                                    }
                                    break;
                                }
                            case 'm_local_hold_nok':
                                {
                                    if (e.session == callSession) {
                                        callSession.bTransfering = false;
                                        btnHoldResume.value = 'Hold';
                                        btnHoldResume.disabled = false;
                                        txtCallStatus.innerHTML = '<i>Failed to place remote party on hold</i>';
                                    }
                                    break;
                                }
                            case 'm_local_resume_ok':
                                {
                                    if (e.session == callSession) {
                                        callSession.bTransfering = false;
                                        btnHoldResume.value = 'Hold';
                                        btnHoldResume.disabled = false;
                                        txtCallStatus.innerHTML = '<i>Call taken off hold</i>';
                                        callSession.bHeld = false;

                                        if (SIPml.isWebRtc4AllSupported()) { // IE don't provide stream callback yet
                                            uiVideoDisplayEvent(false, true);
                                            uiVideoDisplayEvent(true, true);
                                        }
                                    }
                                    break;
                                }
                            case 'm_local_resume_nok':
                                {
                                    if (e.session == callSession) {
                                        callSession.bTransfering = false;
                                        btnHoldResume.disabled = false;
                                        txtCallStatus.innerHTML = '<i>Failed to unhold call</i>';
                                    }
                                    break;
                                }
                            case 'm_remote_hold':
                                {
                                    if (e.session == callSession) {
                                        txtCallStatus.innerHTML = '<i>Placed on hold by remote party</i>';
                                    }
                                    break;
                                }
                            case 'm_remote_resume':
                                {
                                    if (e.session == callSession) {
                                        txtCallStatus.innerHTML = '<i>Taken off hold by remote party</i>';
                                    }
                                    break;
                                }
                            case 'm_bfcp_info':
                                {
                                    if (e.session == callSession) {
                                        txtCallStatus.innerHTML = 'BFCP Info: <i>' + e.description + '</i>';
                                    }
                                    break;
                                }

                            case 'o_ect_trying':
                                {
                                    if (e.session == callSession) {
                                        txtCallStatus.innerHTML = '<i>Call transfer in progress...</i>';
                                    }
                                    break;
                                }
                            case 'o_ect_accepted':
                                {
                                    if (e.session == callSession) {
                                        txtCallStatus.innerHTML = '<i>Call transfer accepted</i>';
                                    }
                                    break;
                                }
                            case 'o_ect_completed':
                            case 'i_ect_completed':
                                {
                                    if (e.session == callSession) {
                                        txtCallStatus.innerHTML = '<i>Call transfer completed</i>';
                                        btnTransfer.disabled = false;
                                        if (oSipSessionTransferCall) {
                                            callSession = oSipSessionTransferCall;
                                        }
                                        oSipSessionTransferCall = null;
                                    }
                                    break;
                                }
                            case 'o_ect_failed':
                            case 'i_ect_failed':
                                {
                                    if (e.session == oSipSessionCall) {
                                        txtCallStatus.innerHTML = '<i>Call transfer failed</i>';
                                        btnTransfer.disabled = false;
                                    }
                                    break;
                                }
                            case 'o_ect_notify':
                            case 'i_ect_notify':
                                {
                                    if (e.session == callSession) {
                                        txtCallStatus.innerHTML = "<i>Call Transfer: <b>" + e.getSipResponseCode() + " " + e.description + "</b></i>";
                                        if (e.getSipResponseCode() >= 300) {
                                            if (callSession.bHeld) {
                                                callSession.resume();
                                            }
                                            btnTransfer.disabled = false;
                                        }
                                    }
                                    break;
                                }
                            case 'i_ect_requested':
                                {
                                    if (e.session == callSession) {
                                        var s_message = "Do you accept call transfer to [" + e.getTransferDestinationFriendlyName() + "]?";//FIXME
                                        if (confirm(s_message)) {
                                            txtCallStatus.innerHTML = "<i>Call transfer in progress...</i>";
                                            callSession.acceptTransfer();
                                            break;
                                        }
                                        callSession.rejectTransfer();
                                    }
                                    break;
                                }
                        }
                    }

                    var callSession;
                    var eventsListener = function(e){
                        console.info('session event = ' + e.type);
                        if(e.type == "connecting"){
                            startRingbackTone();
                            document.getElementById("txtCallStatus").innerHTML = 'Connecting..';
                        }
                        if(e.type == 'm_early_media'){
                            stopRingbackTone();
                            document.getElementById("txtCallStatus").innerHTML = 'In Call..';
                        }
                        if(e.type == 'terminating' || e.type == 'terminated'){
                            stopRingbackTone();
                            document.getElementById("txtCallStatus").innerHTML = 'Terminated.';
                        }

                    }
                    function makeCall(){
                        callSession = sipStack.newSession('call-audio', {
                            audio_remote: document.getElementById('audio_remote'),
                            events_listener: { events: '*', listener: eventsListener } // optional: '*' means all events
                        });
                        callSession.call('3333');
                    }

                    
                    // terminates the call (SIP BYE or CANCEL)
                    function sipHangUp() {
                        if (callSession) {
                            //txtCallStatus.innerHTML = '<i>Terminating the call...</i>';
                            document.getElementById("txtCallStatus").innerHTML = 'Terminating the call...';
                            callSession.hangup({ events_listener: { events: '*', listener: eventsListener } });
                        }
                    }

                    function startRingTone() {
                        try { ringtone.play(); }
                        catch (e) { }
                    }

                    function stopRingTone() {
                        try { ringtone.pause(); }
                        catch (e) { }
                    }

                    function startRingbackTone() {
                        try { ringbacktone.play(); }
                        catch (e) { }
                    }

                    function stopRingbackTone() {
                        try { ringbacktone.pause(); }
                        catch (e) { }
                    }
                });
            }

            function checkData(callback){
                $.ajax({
                    url: "user/validate/5385552b8303b061e822da887e8e4c60",
                    type: 'GET',
                    dataType: 'json',
                    async: false, // added data type
                    success: function(res) {
                        console.log(res);
                        var sipdata = res;
                        callback(sipdata);

                    },
                    error: function(err){
                        alert(invlide);
                    }
                });
            }

            //callStack();
    </script> 
    </body>
</html>
