<?php

require_once "../comm.php";

?>
<!DOCTYPE html>
<html>
<head>
    <title>Knot.js</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Javascript bind framework knot.js help you." />
    <meta name="keywords" content="javascript, mvvm, framework, data binding, angular.js, knotout.js, ember.js" />

    <meta property="og:locale" content="en_US" />
    <meta property="og:site_name" content="knot.js" />
    <meta property="og:title" content="knot.js" />
    <meta property="og:type" content="website" />

    <link rel="stylesheet" href="../css/site.css">
    <link rel="shortcut icon" href="../img/knot.ico">

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

    <link rel="stylesheet" href="../css/github.css">
    <script src="../js/highlight.pack.js"></script>


    <script src="../js/knot.min.js"></script>

    <link rel="stylesheet" href="../css/tabpage.css">
    <script type="text/cbs" src="../cbs/tabpage.pkg.cbs"></script>
    <script type="text/cbs" src="../cbs/sourceTab.pkg.cbs"></script>
    <script src="../js/tabpage.js"></script>
    <script src="../js/sourceTab.js"></script>

    <script src="../debugger/knot.debug.js"></script>

    <script>
        window.sourceModel = {};
        hljs.initHighlightingOnLoad();
    </script>
</head>
<body>
<?php
$smarty = \Common::getSmarty();
$smarty->assign("page", "tutorial");
$smarty->display("header.tpl");
?>

<section class="content-column content">

<?php
$smarty = \Common::getSmarty();
$smarty->assign("page", 1);
$smarty->display("tutorialMenu.tpl");
?>

    <div class="tutorialContent">
        <h2>Start</h2>
        <h3 id="cbs">CBS</h3>
        <p>
            The first thing you need to know about knot.js is CBS. <br><b>CBS</b> stands for <b>C</b>ascading <b>B</b>inding <b>S</b>heet. It is intended to extract binding logic from your HTML.
            In addition to the clean HTML, you will also get the structured, clean, easily readable data binding logic that defined in CBS.
        </p>
        <p>
            CBS is not only looks like CSS, it works almost in the same manner as CSS.<br>
            Here's a typical CBS:<br>
            <img src="../img/tutorial/t1_2.png">
            <ul>
                <li><span><i>"Selector"</i> is exactly the same as <a href="http://www.w3schools.com/cssref/css_selectors.asp" target="w3schools">CSS selector</a>. </span></li>
                <li><span><i>Access Point</i> is the description of where you want to bind to the target.</span>
                    <ul>
                        <li><span><i>Left Access Point</i> is on the HTML node selected by <i>"Selector"</i>. it can be any properties on the HTML element, or it can be path like "style.backgroundColor". </span></li>
                        <li><span><i>Right Access Point</i> is on the current <i>Data Context</i>. It can be properties or path of value like "address.postCode".
                                And it can also be absolute path of value that starts with "/". In this case, knot.js ignores the current <i>Data Context</i> and get the value from the global scope. </span></li>
                    </ul>
                    <span>In the example above, it bind "value" of #userNameInput to the "name" property of current Data Context </span>
                </li>
                <li><span>There are four <i>Binding Types</i>:</span>
                    <ul>
                        <li><span><b>":"</b> is two-way binding. Any side changes, the other side is updated automatically</span> </li>
                        <li><span><b>"=>"</b> is one-way binding. Only right side is updated (when left is changed) </span> </li>
                        <li><span><b>"<="</b> is one-way binding. Only left side is updated (when right is changed) </span> </li>
                        <li><span><b>"="</b> is one-off binding. It only updates the left side with the value of right side for the very first time </span> </li>
                    </ul>
                    * Just use <i>":"</i> if you don't know which type you should use.
                </li>
                <li><span><i>Data Context</i> is the data you want to bind to the HTML element.</span>
                    <ul>
                        <li><span>Data Content is specified by the Access Point named "dataContext". Here's an example:</span>
                            <div class="codeSegment">
                                <pre><code class="css">body{ dataContext: /model }</code></pre>
                            </div>
                        </li>
                        <li><span>If there's no dataContext is specified, a HTML node inherits it's Data Context from the closest DOM ancestors that has dataContext.</span></li>
                    </ul>
                </li>
            </ul>
        </p>

        <h3 id="example1">Example 1</h3>
        <p>Let's take a look at the "Greeting" example again (with a little bit changes to get Javascript involved):</p>

        <script type="text/cbs" class="exampleCBS">
            /*
            Set the default dataContext of all elements in page to "/greetingModel",
            which is window.greetingModel that created in Javascript
            */
            body{
                dataContext: /greetingModel;
            }

            /*
            Bind "value" of the input to "name".  Since the dataContext is window.greetingModel,
             it is actually bind to window.greetingModel.name.
              Another way to do the same thing is to use absolute path:
              "value[immediately:1]: /greetingModel.name"
              "[immediately:1]" is the binding option, it tells knot.js to update for each of the
              key stroke. We'll talk about it later
              */
            .knot_example input{
                value[immediately:1]: name;
            }

            /*Bind to the textContent of .helloString to show the name after "Hello" */
            .helloString{
                text: name;
            }
        </script>
        <script type="text/javascript" class="exampleJS">
            //Use a simple plain object as the model, the data awareness system will
            //monitor the changes of the object automatically
            window.greetingModel = {name:"Alex"};
        </script>
        <div class="knot_example">
            <h3>Greeting from knot.js (V2)</h3>
            <p>
                <label>Input your name here: </label>
                <input type="text">
            </p>
            <p>
                Hello <b class="helloString"></b>
            </p>
        </div>
        <br/><br/>
        <p> In this example, the input box is bound to the "model.name" , and then "model.name"  is tied up to the text content of a &lt;b&gt; tag(".helloString") to show the value.</p>
        <img src="/img/tutorial/t1_1.png"><br/><br/>

        <p>The magic is done by the codes below, please check the comments in codes to learn more:</p>
        <div id="codePages" knot-debugger-ignore  knot-component="SourceTabPage"></div>

        <h3 id="debugger">Debugger</h3>
        <p>
            One of the cool features comes with knot.js is the <i>Debugger</i>. With <i>Debugger</i>, knot.js is not another mystery black box to you.
            It's transparent, you can see all of the real time statuses as well as the dynamic data flow process. It helps you understand how does knot.js work in short time.<br>
        </p>
        <p>
            <b>Please do utilize <i>Debugger</i> to learn knot.js.</b> Why don't you call out the <i>Debugger</i> now, change something in the example input box and watch how knot.js works in this example?
        </p>
        <p>
            Click on the <img src="../debugger/img/debugger.png" style="height:16px;width: 16px"> button in the bottom left of the Window to bring up <i>Debugger</i> window.
            If it doesn't work, please check you popup blocker setting.<br>
            The image blow explains how to use <i>Debugger</i>
            <img src="../img/tutorial/t1_3.png">

           <br> * Do you know the <i>Debugger</i> it-self is created with knot.js? Check the source files to see how simple and slick knot.js can do!
        </p>

        <h3 id="more">A few more things</h3>
        <ul>
            <li>
                <span>You can also put your options input HTML tag just like how you do it in the other frameworks (I don't think it's a good way, but I agree to disagree). Here's an example:</span>
                <div class="codeSegment">
                    <pre><code class="html">&lt;input type=&quot;text&quot; binding=&quot;value:name&quot;&gt;</code></pre>
                </div>
            </li>
            <li>
                <span>Similar to CSS, binding option apply to all HTML elements that selected by <i>Selector</i>. For example, the CBS below will bind the text content
                    of all of the elements with class "title" to the "title" property of their own <i>Data Context</i> objects.</span>
                <div class="codeSegment">
                    <pre><code class="css">.title{
    text: title
}</code></pre>
                </div>
            </li>
        </ul>

        <div class="footNote">
            <ul>
                <li>The handdrawn style flowchart is created with <a href="http://yuml.me/">yUML</a></li>
                <li>Syntax hight light ability is from <a href="https://highlightjs.org/">highlight.js</a></li>
            </ul>
        </div>
    </div>
</section>

<script type="text/cbs" class="exampleCBS">
    #codePages{
        sourceInfo:/sourceModel.codes
    }
</script>
<script>
    window.SourceCodeHelper.collectSourceCodes(
        [
            {selector:".exampleCBS",title:"CBS", type:"cbs"},
            {selector:".knot_example",title:"HTML", type:"html"},
            {selector:".exampleJS",title:"Javascript", type:"javascript"}
        ],
        function(res){
            window.sourceModel.codes = res;
        });
</script>

<?php
$smarty = \Common::getSmarty();
$smarty->display("footer.tpl");
?>
</body>
</html>