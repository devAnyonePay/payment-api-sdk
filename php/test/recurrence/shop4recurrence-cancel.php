<?php
    include(dirname(__FILE__) . '/../basic_auth.php');

    $testshop_clientId=$_SERVER['anyonepay_testshop_stage_clientId'];
    $testshop_clientPw=$_SERVER['anyonepay_testshop_stage_clientPw'];
?>
<html>

<head>
    <!-- development version, includes helpful console warnings -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
</head>

<body>
    <div id="app">
        <v-app>
            <v-main>
                <v-container>

                    <h1>Fake Merchant Shopping mall</h1>
                    <h3>Test cancel subscription request</h3>
                    <h4>2021.02.04 updated</h4>
                    <br/>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-select
                                :items="profiles"
                                filled
                                label="Profile"
                                v-model="profile"
                                @change="changeProfile"
                            ></v-select>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="ClientId" v-model="clientId"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="ClientSecret" v-model="clientSecret"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Subscription Seq" v-model="subscriptionSeq"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-select
                                :items="processByRedirectUrls"
                                filled
                                label="processByRedirectUrl"
                                v-model="processByRedirectUrl"
                            ></v-select>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Reference No" v-model="refNo"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Redirect-Uri(Return when finished to cancel)" v-model="redirectUri">
                            </v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Webhook-Uri" v-model="webhookUri"></v-text-field>
                            
                            <v-card>
                                <v-card-title primary-title>
                                <div>
                                    <div class="caption mb-0">WebHook will works with below merchant public/private key pairs in memory cache.</div>
                                    <div id="example-1" style="background-color: #DDD;padding:10px; border:1px solid #aaa;">
                                        <div v-for="key in webhookKeys" :key="key.publicKey" style="border:1px solid #111;padding:10px;">
                                            <div class="caption">
                                                <span>Created : </span><span>{{ key.created }}</span>
                                            </div>
                                            <div class="caption"><span>Public SHA1 : </span><span>{{ key.sha1 }}</span></div>
                                        </div>
                                    </div>
                                </div>
                                </v-card-title>
                                <v-card-actions>
                                    <a href="/test/webhook-config.php">Configure of webhook's merchant public, privatekey for testing</a>
                                </v-card-actions>
                            </v-card>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12" md="12">
                            <v-text-field label="Cancel-Uri" v-model="cancelUri"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-btn v-on:click="startpay()" color="primary">Use above info to pay</v-btn>
                    </v-row>

                    <br/>
                    <v-row v-if="isProgressing">
                        <v-progress-circular
                            indeterminate
                            color="primary"
                        ></v-progress-circular>
                    </v-row>
                    <br/>

                    <v-row>
                        <v-textarea
                            name="input-7-1"
                            label="Result of Request"
                            v-model="resultOfRequest"
                            hint="Hint text"
                        ></v-textarea>
                    </v-row>

                    <v-row v-if="moveToCheckoutPage">
                        <v-btn v-on:click="goCheckoutNow()" color="primary">Go Checkout Now</v-btn>
                    </v-row>

                </v-container>
            </v-main>
        </v-app>
    </div>
</body>
<script>
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function setCookie(cname, cvalue, exdays) {
        var pathname = window.location.pathname;

        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path="+pathname;
    }

    function saveCookie(prof, s) {
        setCookie("ANYONE_PROFILE_"+prof, JSON.stringify(s));
    }

    function loadCookie(prof) {
        var x = getCookie("ANYONE_PROFILE_"+prof);
        if (!x) return;
        return JSON.parse(x);
    }

    var app = new Vue({
        el: '#app',
        vuetify: new Vuetify(),
        mounted: function () {
            this.loadCache();
            this.loadWebhookKeys();
        },
        data: {
            isProgressing : false,
            profiles : [
                'STAGE', 'SANDBOX', 'PRODUCTION_INTERNAL'
            ],
            profile : 'STAGE',

            subscriptionSeq : '',

            processByRedirectUrls : [
                'TRUE', 'FALSE'
            ],
            processByRedirectUrl : 'TRUE',

            clientId: "<?php echo $testshop_clientId?>",
            clientSecret: "<?php echo $testshop_clientPw?>",
            
            refNo: String((new Date()).getTime()),
            
            redirectUri: 'http://testshop.anyonepay.ph/test/payResult.php?v=1',
            webhookUri: 'http://testshop.anyonepay.ph/test/webhook.php?v=verifyOrCompletion&call=STAGE',
            cancelUri: 'http://testshop.anyonepay.ph/test/payResult.php?v=cancel',

            resultOfRequest: '',
            moveToCheckoutPage: '',
            // ---------------------------------------------------------------------------------------------------------
            webhookKeys: [],
        },
        methods: {
            loadCache: function(){
                var ck = loadCookie(this.profile);
                if (!ck) return;
                for (var k in ck) {
                    if (this[k]) {
                        this[k] = ck[k];
                    }
                }
                this.refNo = String((new Date()).getTime());
            },
            changeProfile: function() {
                this.loadCache();
            },
            startpay: function () {
                var vue = this;

                var dt = {
                    profile : this.profile,

                    clientId: this.clientId,
                    clientSecret: this.clientSecret,

                    subscriptionSeq: this.subscriptionSeq,
                    
                    processByRedirectUrl: this.processByRedirectUrl,

                    refNo: this.refNo,

                    redirectUri: this.redirectUri,
                    webhookUri: this.webhookUri,
                    cancelUri: this.cancelUri,
                };
                console.log('this parameter will be requested:', dt);
                saveCookie(vue.profile, dt);

                vue.resultOfRequest = "Requesting...";
                vue.isProgressing = true;


                $.ajax({
                    type: "POST",
                    url: "/test/recurrence/cancelRecurrence.php",
                    contentType:"application/json; charset=utf-8",
                    data: JSON.stringify(dt),
                    success : function(data){
                        vue.resultOfRequest = JSON.stringify(data);
                        vue.moveToCheckoutPage = data.checkoutUrl;
                        vue.isProgressing = false;
                    },
                    error: function(e){
                        vue.resultOfRequest = JSON.stringify(e);
                        vue.moveToCheckoutPage = '';
                        vue.isProgressing = false;
                    }
                });
            },
            goCheckoutNow : function(){
                location.href = this.moveToCheckoutPage;
            },
            loadWebhookKeys: function(){
                var vue = this;
                vue.isProgressing = true;

                $.ajax({
                    type: "GET",
                    url: "/test/webhook-cache.php",
                    contentType:"application/json; charset=utf-8",
                    success : function(data){
                        vue.webhookKeys = data;
                        vue.isProgressing = false;
                    },
                    error: function(e){
                        alert('Error to get webhookKeys from cache.');
                        vue.webhookKeys = [];
                        vue.isProgressing = false;
                    }
                });
            },
        }
    });
</script>

</html>