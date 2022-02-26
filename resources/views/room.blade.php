<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Agora Mute-Unmute Remote User Demo</title>
    <link rel="icon" href="./favicon.png" type="image/png" />
    <link rel="apple-touch-icon" href="./apple-touch-icon.png" type="image/png" />

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('assets/default/css/style.css') }}" />
</head>

<body>
    <!-- Container -->
    <div class="container-fluid banner">
        <p class="banner-text text-center fw-bold bg-primary">MeTutor Meeting Room</p>
    </div>

    <div class="container mt-5 mb-5">
        <!-- Form for joining and leaving -->
        <form id="join-form">
            <div class="row join-info-group">

                <input id="appid" type="hidden" placeholder="Enter AppID" value="1ecdce97fc084c33a33ebab510dc110b"
                    required class="form-control" />

                <input id="channel" type="hidden" value="my-channel" placeholder="Enter Channel Name" required
                    class="form-control" />

                <input id="accountName" type="hidden" value="User" placeholder="Enter Your Name" required
                    class="form-control" />
                {{-- </div> --}}
                <input type="hidden" id="token"
                    value="0061ecdce97fc084c33a33ebab510dc110bIADuDAOCw+bfF/+z00HNblFzv2DXMp+HXHHfK7OUscC7voa0dcYAAAAAIgDzxwcSi2MOYgQAAQCMYw5iAgCMYw5iAwCMYw5iBACMYw5i" />
            </div>

            <div class="button-group my-3">
                <button id="join" type="submit" class="btn btn-sm">Join Meeting</button>
                <button id="mic-btn" type="button" class="btn" disabled>
                    <i id="mic-icon" class="fa fa-microphone"></i>
                </button>
                <button id="video-btn" type="button" class="btn text-white bg-success" disabled>
                    <i id="video-icon" class="fa fa-video"></i>
                </button>
                <button id="leave" type="button" class="btn bg-danger text-white" disabled>
                    <i id="end-call" class="fa fa-phone"></i>
                </button>
            </div>
        </form>
        <div class="row">
            <div class="col">
                <div class="row video-group">
                    <!-- Local Video -->
                    <div class="col">
                        <p id="local-player-name" class="player-name"></p>
                        <div id="local-player" class="player"></div>
                    </div>
                    {{-- <div class="w-100"></div> --}}
                    <!-- Remote Players -->
                    <div class="col">
                        <div id="remote-playerlist"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous">
    </script>
    <script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/agora-rtm-sdk@1.3.1/index.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/md5.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
    <script>
        var encryptedName = "";
        var decryptedName = "";

        // Create Agora client
        var client = AgoraRTC.createClient({
            mode: "rtc",
            codec: "vp8"
        });

        // RTM Global Vars
        var isLoggedIn = false;

        var localTracks = {
            videoTrack: null,
            audioTrack: null
        };
        var remoteUsers = {};
        // Agora client options
        var options = {
            appid: "1ecdce97fc084c33a33ebab510dc110b",
            channel: "my-channel",
            uid: null,
            token: "0061ecdce97fc084c33a33ebab510dc110bIABNRZaHdTI+YtIdMwi4ei/EWFm2BGfpEGiyFO5u4XjtKoa0dcYAAAAAEADzxwcSGS4OYgEAAQAZLg5i",
            accountName: "null"
        };

        // let meetingLink;


        $("#join-form").submit(async function(e) {
            e.preventDefault();

            $("#join").attr("disabled", true);
            try {
                options.appid = $("#appid").val();
                options.token = $("#token").val();
                options.channel = $("#channel").val();
                options.accountName = $('#accountName').val();

                encryptedName = CryptoJS.AES.encrypt(options.accountName, "%");
                alert(encryptedName);


                // let s_no = Math.random().toString(36).slice(2);
                // console.log(s_no, "Random string");
                // meetingLink = "http://localhost/metutor_brain/public/join-meeting" + s_no;
                // alert(meetingLink);
                window.location.replace("/metutor_brain/public/join-meeting/" + encryptedName);

                await join();
            } catch (error) {
                console.error(error);
            } finally {
                $("#leave").attr("disabled", false);
            }

        });

        let pageURL = $(location).attr("href");
        if (pageURL != "http://localhost/metutor_brain/public/join-meeting") {
            slicedUrl = pageURL.slice(51, );
            decryptedName = (CryptoJS.AES.decrypt(slicedUrl, "%")).toString(CryptoJS.enc.Utf8);
            options.accountName = $('#accountName').val();
            // alert(decryptedName);
            if (decryptedName == options.accountName) {
                options.appid = $("#appid").val();
                options.token = $("#token").val();
                options.channel = $("#channel").val();

                $("#join").attr("disabled", true);
                $("#leave").attr("disabled", false);
                join();
            }
        }

        $("#leave").click(function(e) {
            leave();
        })

        async function join() {
            $("#mic-btn").prop("disabled", false);
            $("#video-btn").prop("disabled", false);
            RTMJoin();
            // add event listener to play remote tracks when remote user publishs.
            client.on("user-published", handleUserPublished);
            client.on("user-left", handleUserLeft);
            // join a channel and create local tracks, we can use Promise.all to run them concurrently
            [options.uid, localTracks.audioTrack, localTracks.videoTrack] = await Promise.all([
                // join the channel
                client.join(options.appid, options.channel, options.token || null),
                // create local tracks, using microphone and camera
                AgoraRTC.createMicrophoneAudioTrack(),
                AgoraRTC.createCameraVideoTrack()
            ]);
            // play local video track
            localTracks.videoTrack.play("local-player");
            $("#local-player-name").text(`LocalVideo (${options.accountName})`);
            // publish local tracks to channel
            await client.publish(Object.values(localTracks));
            console.log("publish success");
        }
        async function leave() {
            for (trackName in localTracks) {
                var track = localTracks[trackName];
                if (track) {
                    track.stop();
                    track.close();
                    $('#mic-btn').prop('disabled', true);
                    $('#video-btn').prop('disabled', true);
                    localTracks[trackName] = undefined;
                }
            }
            // remove remote users and player views
            remoteUsers = {};
            $("#remote-playerlist").html("");
            // leave the channel
            await client.leave();
            $("#local-player-name").text("");
            $("#join").attr("disabled", false);
            $("#leave").attr("disabled", true);
            console.log("client leaves channel success");
        }

        async function subscribe(user, mediaType) {
            const uid = user.uid;
            console.log(user, "user");
            // subscribe to a remote user
            await client.subscribe(user, mediaType);
            console.log("subscribe success");
            if (mediaType === 'video') {
                const player = $(`
                <div id="player-wrapper-${uid}">
                    <p class="player-name">RemoteUser (${uid})</p>
                    <div id="player-${uid}" class="player"></div>
                </div>
            `);
                $("#remote-playerlist").append(player);
                user.videoTrack.play(`player-${uid}`);
            }
            if (mediaType === 'audio') {
                user.audioTrack.play();
            }
        }

        // Handle user publish
        function handleUserPublished(user, mediaType) {
            const id = user.uid;
            remoteUsers[id] = user;
            subscribe(user, mediaType);
        }

        // Handle user left
        function handleUserLeft(user) {
            const id = user.uid;
            delete remoteUsers[id];
            $(`#player-wrapper-${id}`).remove();
        }

        // Initialise UI controls
        enableUiControls();

        // Action buttons
        function enableUiControls() {
            $("#mic-btn").click(function() {
                toggleMic();
            });
            $("#video-btn").click(function() {
                toggleVideo();
            });
        }

        // Toggle Mic
        function toggleMic() {
            if ($("#mic-icon").hasClass('fa-microphone')) {
                localTracks.audioTrack.setEnabled(false);
                console.log("Audio Muted.");
            } else {
                localTracks.audioTrack.setEnabled(true);
                console.log("Audio Unmuted.");
            }
            $("#mic-icon").toggleClass('fa-microphone').toggleClass('fa-microphone-slash');
        }

        // Toggle Video
        function toggleVideo() {
            if ($("#video-icon").hasClass('fa-video')) {
                localTracks.videoTrack.setEnabled(false);
                console.log("Video Muted.");
            } else {
                localTracks.videoTrack.setEnabled(true);
                console.log("Video Unmuted.");
            }
            $("#video-icon").toggleClass('fa-video').toggleClass('fa-video-slash');
        }

        async function RTMJoin() {
            // Create Agora RTM client
            const clientRTM = AgoraRTM.createInstance($("#appid").val(), {
                enableLogUpload: false
            });
            var accountName = $('#accountName').val();
            // Login
            clientRTM.login({
                uid: accountName
            }).then(() => {
                console.log('AgoraRTM client login success. Username: ' + accountName);
                isLoggedIn = true;
                // RTM Channel Join
                var channelName = $('#channel').val();
                channel = clientRTM.createChannel(channelName);
                channel.join().then(() => {
                    console.log('AgoraRTM client channel join success.');
                    // Get all members in RTM Channel
                    channel.getMembers().then((memberNames) => {
                        console.log("------------------------------");
                        console.log("All members in the channel are as follows: ");
                        console.log(memberNames);
                        var newHTML = $.map(memberNames, function(singleMember) {
                            if (singleMember != accountName) {
                                return (`<li class="mt-2">
                  <div class="row">
                      <p>${singleMember}</p>
                   </div>
                   <div class="mb-4">
                     <button class="text-white btn btn-control mx-3 remoteMicrophone micOn" id="remoteAudio-${singleMember}">Toggle Mic</button>
                     <button class="text-white btn btn-control remoteCamera camOn" id="remoteVideo-${singleMember}">Toggle Video</button>
                    </div>
                 </li>`);
                            }
                        });
                        $("#insert-all-users").html(newHTML.join(""));
                    });
                    // Send peer-to-peer message for audio muting and unmuting
                    $(document).on('click', '.remoteMicrophone', function() {
                        fullDivId = $(this).attr('id');
                        peerId = fullDivId.substring(fullDivId.indexOf("-") + 1);
                        console.log("Remote microphone button pressed.");
                        let peerMessage = "audio";
                        clientRTM.sendMessageToPeer({
                                text: peerMessage
                            },
                            peerId,
                        ).then(sendResult => {
                            if (sendResult.hasPeerReceived) {
                                console.log("Message has been received by: " +
                                    peerId +
                                    " Message: " + peerMessage);
                            } else {
                                console.log("Message sent to: " + peerId +
                                    " Message: " + peerMessage);
                            }
                        })
                    });
                    // Send peer-to-peer message for video muting and unmuting
                    $(document).on('click', '.remoteCamera', function() {
                        fullDivId = $(this).attr('id');
                        peerId = fullDivId.substring(fullDivId.indexOf("-") + 1);
                        console.log("Remote video button pressed.");
                        let peerMessage = "video";
                        clientRTM.sendMessageToPeer({
                                text: peerMessage
                            },
                            peerId,
                        ).then(sendResult => {
                            if (sendResult.hasPeerReceived) {
                                console.log("Message has been received by: " +
                                    peerId +
                                    " Message: " + peerMessage);
                            } else {
                                console.log("Message sent to: " + peerId +
                                    " Message: " + peerMessage);
                            }
                        })
                    });
                    // Display messages from peer
                    clientRTM.on('MessageFromPeer', function({
                        text
                    }, peerId) {
                        console.log(peerId + " muted/unmuted your " + text);
                        if (text == "audio") {
                            console.log("Remote video toggle reached with " + peerId);
                            if ($("#remoteAudio-" + peerId).hasClass('micOn')) {
                                localTracks.audioTrack.setEnabled(false);
                                console.log("Remote Audio Muted for: " + peerId);
                                $("#remoteAudio-" + peerId).removeClass('micOn');
                            } else {
                                localTracks.audioTrack.setEnabled(true);
                                console.log("Remote Audio Unmuted for: " + peerId);
                                $("#remoteAudio-" + peerId).addClass('micOn');
                            }
                        } else if (text == "video") {
                            console.log("Remote video toggle reached with " + peerId);
                            if ($("#remoteVideo-" + peerId).hasClass('camOn')) {
                                localTracks.videoTrack.setEnabled(false);
                                console.log("Remote Video Muted for: " + peerId);
                                $("#remoteVideo-" + peerId).removeClass('camOn');
                            } else {
                                localTracks.videoTrack.setEnabled(true);
                                console.log("Remote Video Unmuted for: " + peerId);
                                $("#remoteVideo-" + peerId).addClass('camOn');
                            }
                        }
                    })
                    // Display channel member joined updated users
                    channel.on('MemberJoined', function() {
                        // Get all members in RTM Channel
                        channel.getMembers().then((memberNames) => {
                            console.log("New member joined so updated list is: ");
                            console.log(memberNames);
                            var newHTML = $.map(memberNames, function(
                                singleMember) {
                                if (singleMember != accountName) {
                                    return (`<li class="mt-2">
                      <div class="row">
                          <p>${singleMember}</p>
                       </div>
                       <div class="mb-4">
                         <button class="text-white btn btn-control mx-3 remoteMicrophone micOn" id="remoteAudio-${singleMember}">Toggle Mic</button>
                         <button class="text-white btn btn-control remoteCamera camOn" id="remoteVideo-${singleMember}">Toggle Video</button>
                        </div>
                     </li>`);
                                }
                            });
                            $("#insert-all-users").html(newHTML.join(""));
                        });
                    })
                    // Display channel member left updated users
                    channel.on('MemberLeft', function() {
                        // Get all members in RTM Channel
                        channel.getMembers().then((memberNames) => {
                            console.log("A member left so updated list is: ");
                            console.log(memberNames);
                            var newHTML = $.map(memberNames, function(
                                singleMember) {
                                if (singleMember != accountName) {
                                    return (`<li class="mt-2">
                      <div class="row">
                          <p>${singleMember}</p>
                       </div>
                       <div class="mb-4">
                         <button class="text-white btn btn-control mx-3 remoteMicrophone micOn" id="remoteAudio-${singleMember}">Toggle Mic</button>
                         <button class="text-white btn btn-control remoteCamera camOn" id="remoteVideo-${singleMember}">Toggle Video</button>
                        </div>
                     </li>`);
                                }
                            });
                            $("#insert-all-users").html(newHTML.join(""));
                        });
                    });
                }).catch(error => {
                    console.log('AgoraRTM client channel join failed: ', error);
                }).catch(err => {
                    console.log('AgoraRTM client login failure: ', err);
                });
            });
            // Logout
            document.getElementById("leave").onclick = async function() {
                console.log("Client logged out of RTM.");
                await clientRTM.logout();
            }
        }
    </script>
</body>

</html>
