<html>

<body>
    <div>
        <?php
        echo "PayResult.....finished to pay"
        // http://testshop.anyonepay.ph/payResult.php?v=1?paymentStatus=SUCCESS&paymentSeq=2009172119445941638&referenceNo=1600348731406
        ?>
        <h4>
            Please check below parameters are<br />
            paymentStatus, paymentSeq, referenceNo
        </h4>
        <pre>
            <?php
            $parameter = $_SERVER['QUERY_STRING'];
            echo $parameter;
            ?>
        </pre>
    </div>
    <a href="https://anyonepay.readme.io/reference#registerpayment">
        <h2>Return to API document page</h2>
    </a>
    <br/>
    <a href="/test/shop.html">
        <h3>Move to test-shop (Need authority)</h3>
    </a>
</body>

</html>