<script src="https://imasdk.googleapis.com/js/sdkloader/ima3.js"></script>
<script type="text/javascript">
    var player = videojs('player');

    // Cue points for VOD (in seconds)
    var vodCuePoints = [0, 60, 300]; // pre-roll, mid-rolls
    var liveAdInterval = 900; // 15 minutes for live

    // Determine if content is live or VOD
    var isLive = player.currentSrc().includes("m3u8");

    // Function to return cust_params for GAM targeting
    function getAdParams(currentTime, isLive) {
        if(isLive) return "ad_type=pre&position=pre&station=radio47&content=live";
        if(currentTime === 0) return "ad_type=skippable&position=pre&station=radio47&content=vod";
        if(currentTime === 60) return "ad_type=nonskippable&position=mid&station=radio47&content=vod";
        if(currentTime >= player.duration() - 1) return "ad_type=bumper&position=post&station=radio47&content=vod";
        return "";
    }

    // Function to generate VAST tag with dynamic params
    function generateAdTag(currentTime) {
        return "https://pubads.g.doubleclick.net/gampad/ads?" +
            "iu=/123456789/AudioVideoUnit" +
            "&description_url=" + encodeURIComponent(window.location.href) +
            "&env=vp&output=vast&unviewed_position_start=1&sz=640x480" +
            "&cust_params=" + encodeURIComponent(getAdParams(currentTime, isLive));
    }

    // Initialize IMA plugin
    player.ima({
        id: 'player',
        adTagUrl: generateAdTag(0),
        companionDiv: 'companion-container',
        debug: true
    });

    // VOD: trigger mid-rolls based on cue points
    if(!isLive){
        player.on('timeupdate', function() {
            var currentTime = Math.floor(player.currentTime());
            if(vodCuePoints.includes(currentTime) && !player.ads.currentAd()){
                player.ima.changeAdTag(generateAdTag(currentTime));
                player.ima.requestAds();
            }
        });
    }

    // Live: trigger ads on intervals (e.g., every 15 minutes)
    if(isLive){
        setInterval(() => {
            if(!player.ads.currentAd()){
                player.ima.changeAdTag(generateAdTag(0));
                player.ima.requestAds();
            }
        }, liveAdInterval * 1000);
    }
</script>
