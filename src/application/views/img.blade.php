
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title></title>
    <script type="text/javascript">
        try {
            /** parse location.search to an Object **/
            var queryToJson = function(QS, isDecode) {
                var _Qlist = QS.split("&");
                var _json = {};
                for (var i = 0, len = _Qlist.length; i < len; i++) {
                    var _hsh = _Qlist[i].split("=");
                    if (!_json[_hsh[0]]) {
                        _json[_hsh[0]] = _hsh[1];
                    } else {
                        _json[_hsh[0]] = [_hsh[1]].concat(_json[_hsh[0]]);
                    }
                }
                return _json;
            };
            var query = window.location.search.slice(1);
            var res = queryToJson(query, true);
            var func = res['callback'];
            if (window.parent) {
                window.parent[func](res, query);
            }
        } catch(e) {
        }
    </script>
</head>
<body>

</body>
</html>
