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

    <script src="/test/js/jquery-1.12.4.min.js" ></script>
</head>

<body>
    <div id="app">
        <v-app>
            <v-main>
                <v-container>

                    <h1>Fake Merchant Shopping mall</h1>
                    <h3>Test result of payment</h3>
                    <h4>2020.10.21 updated</h4>
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
                            <v-text-field label="PaymentSeq" v-model="paymentSeq"></v-text-field>
                        </v-col>
                    </v-row>
                    
                    <v-row>
                        <v-btn v-on:click="startGetResult()" color="primary">Peek result of payment</v-btn>
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
                            label="Result of Verify"
                            v-model="resultOfVerify"
                            hint="Hint text"
                        ></v-textarea>
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
        },
        data: {
            isProgressing : false,
            profiles : [
                'STAGE', 'SANDBOX', 'PRODUCTION_INTERNAL'
            ],
            profile : 'STAGE',

            clientId: "<?php echo $testshop_clientId?>",
            clientSecret: "<?php echo $testshop_clientPw?>",

            paymentSeq: "2010201528114661381",
            resultOfVerify : '',
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

            startGetResult: function () {
                var vue = this;
                var dt = {
                    profile : this.profile,

                    clientId: this.clientId,
                    clientSecret: this.clientSecret,

                    paymentSeq: this.paymentSeq
                };
                console.log('this parameter will be requested:', dt);
                saveCookie(vue.profile, dt);

                vue.resultOfVerify = "Requesting...";
                vue.isProgressing = true;

                $.ajax({
                    type: "POST",
                    url: "/test/oneTime/retrievePayment.php",
                    contentType:"application/json; charset=utf-8",
                    data: JSON.stringify(dt),
                    success : function(data){
                        vue.resultOfVerify = JSON.stringify(data);
                        vue.isProgressing = false;
                    },
                    error: function(e){
                        vue.resultOfVerify = JSON.stringify(e);
                        vue.isProgressing = false;
                    }
                });
                
            }
        }
    });
</script>

</html>