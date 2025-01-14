<?php
if (!defined('ABSPATH')) {
  exit;
}
?>
<?php
echo '<style>
.container {
    width: 100%;
    max-width: 900px;
    padding-right: 20px;
    padding-left: 20px;
}
.invoice {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.1), 0 10px 10px rgba(0, 0, 0, 0.13);
    margin: 50px 0;
    padding: 50px 30px 30px;
}
.invoice header {
    overflow: hidden;
    margin-bottom: 60px;
}
.invoice header section:nth-of-type(1) {
    float: left;
}
.invoice header section:nth-of-type(1) h1 {
    text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 2px;
        color: #344760;
        font-size: 25px;
        margin-top: 0;
        margin-bottom: 5px;
}
.invoice header section:nth-of-type(1) span {
    color: #b7bcc3;
    font-size: 14px;
    letter-spacing: 2px;
}
.invoice header section:nth-of-type(2) {
    float: right;
}
.invoice header section:nth-of-type(2) span {
    font-size: 21px;
    color: #b7bcc3;
    letter-spacing: 1px;
}
.invoice header section:nth-of-type(2) span:before {
    content: "#";
}
.invoice main {
    border: 1px dashed #b7bcc3;
    border-left-width: 0px;
    border-right-width: 0px;
    padding-top: 30px;
    padding-bottom: 30px;
}
.invoice main section {
    overflow: hidden;
}
.invoice main section span {
    float: left;
    color: #344760;
    font-size: 16px;
    letter-spacing: .5px;
}
.invoice main section span:nth-of-type(1) {
    width: 40%;
    margin-right: 5%;
}
.invoice main section span:nth-of-type(2) {
    width: calc(50% / 4);
    margin-right: 1%;
}
.invoice main section span:nth-of-type(3) , .invoice main section span:nth-of-type(4), .invoice main section span:nth-of-type(5)  {
    text-align: right;
}
.invoice main section span:nth-of-type(3) , .invoice main section span:nth-of-type(4), .invoice main section span:nth-of-type(5)  {
    width: calc(50% / 4);
    margin-right: 1%;
}

.invoice main section:nth-of-type(1) {
    margin-bottom: 30px;
}
.invoice main section:nth-of-type(1) span {
    color: #b7bcc3;
    text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 13px;
}
.invoice main section:nth-of-type(2) {
    margin-bottom: 30px;
}
.invoice main section:nth-of-type(2) figure {
    overflow: hidden;
    margin: 0;
    margin-bottom: 20px;
    line-height: 160%;
}
.invoice main section:nth-of-type(2) figure:last-of-type {
    margin-bottom: 0;
}
.invoice main section:nth-of-type(3) span:nth-of-type(1), .invoice main section:nth-of-type(4) span:nth-of-type(1),
.invoice main section:nth-of-type(5) span:nth-of-type(1),
.invoice main section:nth-of-type(6) span:nth-of-type(1)   {
    width: 72.5%;
    font-weight: bold;
    text-align:right;
}
.invoice main section:nth-of-type(3) span:nth-of-type(2),
.invoice main section:nth-of-type(4) span:nth-of-type(2),
.invoice main section:nth-of-type(5) span:nth-of-type(2),
.invoice main section:nth-of-type(6) span:nth-of-type(2) {
    margin-right: 0 !important;
    text-align:right;
}

.invoice footer {
    text-align: center;
    margin-top: 30px;
}
.invoice footer a {
    font-size: 19px;
    font-weight: bold;
    text-decoration: none;
    text-transform: uppercase;
        position: relative;
        letter-spacing: 1px;
        width: 30%;
        display: inline-block;
}
.invoice footer a:after {
    content: "";
    width: 0%;
    height: 4px;
    position: absolute;
    right: 0;
    bottom: -10px;
    background-color: inherit;
    -webkit-transition: width 0.2s ease-in-out;
    -moz-transition: width 0.2s ease-in-out;
    transition: width 0.2s ease-in-out;
}
.invoice footer a:hover:after {
    width: 100%;
}
.invoice footer a:nth-of-type(1) {
    color: #b7bcc3;
    margin-right: 30px;
}
.invoice footer a:nth-of-type(1):after {
    background-color: #b7bcc3;
}
.invoice footer a:nth-of-type(2) {
    color: #fe8888;
}
.invoice footer a:nth-of-type(2):after {
    background-color: #fe8888;
}

</style>';