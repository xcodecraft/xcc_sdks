TAG=`cat src/version.txt`
echo $TAG ;
/data/x/tools/mara-pub/rocket_pub.sh  --prj xcc_sdks --tag $TAG  --host $*
