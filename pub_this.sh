TAG=`cat src/version.txt`
echo $TAG ;
cd $HOME/devspace/mara-pub ;
./rocket_pub.sh  --prj xcc_sdks --tag $TAG  --host $*
