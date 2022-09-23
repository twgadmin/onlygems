<link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.css" rel="stylesheet"/>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.js"></script>

<div class="container">
  <div class="form-group ui-widget">
    <label>Languages</label>
    <input class="form-control autocomplete" placeholder="Enter A" />
  </div>

  <div class="form-group ui-widget">
    <label >Another Field</label>
    <input class="form-control" />
  </div>
</div>

<style>

.ui-autocomplete {
    position: absolute;
    z-index: 1000;
    cursor: default;
    padding: 0;
    margin-top: 2px;
    list-style: none;
    background-color: #ffffff;
    border: 1px solid #ccc
    -webkit-border-radius: 5px;
       -moz-border-radius: 5px;
            border-radius: 5px;
    -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
       -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}
.ui-autocomplete > li {
  padding: 3px 20px;
}
.ui-autocomplete > li.ui-state-focus {
  background-color: #DDD;
}
.ui-helper-hidden-accessible {
  display: none;
}

</style>

<script>
    $(function() {
        var availableTags = [
        "<div class='alert alert-success'>ActionScript</div>", "AppleScript", "Asp", "BASIC", "C", "C++",
        "Clojure", "COBOL", "ColdFusion", "Erlang", "Fortran",
        "Groovy", "Haskell", "Java", "JavaScript", "Lisp", "Perl",
        "PHP", "Python", "Ruby", "Scala", "Scheme"
        ];

        $(".autocomplete").autocomplete({
        source: availableTags
        });
    });
  </script>
