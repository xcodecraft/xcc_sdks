env=$1
case $env in
    "beta" )
        domain="beta.plato.w-svc.io"
        expect='{"errno":0,"errmsg":"","data":{"self_monitor":{"title":"1024","__subs":["a"],"__ver":"2"}}}'
        ;;
    "demo" )
        domain="demo.plato.w-svc.io"
        expect='{"errno":0,"errmsg":"","data":{"self_monitor":{"title":"360","__subs":["a"],"__ver":"2"}}}'
        ;;
    *)
        domain="plato.w-svc.io"
        expect='{"errno":0,"errmsg":"","data":{"self_monitor":{"title":"1024","__subs":["a"],"__ver":"2"}}}'
esac
content=`curl "http://$domain:8360/env/online/scene/self_monitor"  -s -x 127.0.0.1:8087`
if  test "$content" != "$expect"  ; then 
    host=`hostname`
    echo "            [failed] -- localproxy test : $host $domain"
    exit -1 ;
fi
