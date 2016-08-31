adirname() { odir=`pwd`; cd `dirname $1`; pwd; cd "${odir}"; }
MYDIR=`adirname "$0"`
cd $MYDIR ;

envname=$1

/data/x/svcs/local_proxy/bin/import_core.sh ./vcl/plato_demo.vcl
/data/x/svcs/local_proxy/bin/import_core.sh ./vcl/plato_online.vcl
./conf/sdk_inst.sh $*
# ./modules/setup.sh $*
./bin/prjs_restart.sh 

