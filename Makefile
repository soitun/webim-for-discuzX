#Release webim

PRIFIX= .
SRC_DIR= ${PRIFIX}
DIST_DIR= ${PRIFIX}/dist
LIB_DIR= ${PRIFIX}/lib
VERSION= 0.1.0
PRODUCT_NAME= discuzX
CACHE_DIR=${PRIFIX}/webim
REL_FILE = ${DIST_DIR}/WebIM_For_${PRODUCT_NAME}-${VERSION}.zip
CONFIG_FILE=${SRC_DIR}/discuz_plugin_webim.xml

SRC_FILES = ${SRC_DIR}/*.php \
	    ${SRC_DIR}/*.md \
	    ${SRC_DIR}/lib \
	    ${SRC_DIR}/static \
	    ${SRC_DIR}/template \

all: ${REL_FILE}
	@@echo "Build complete."

${REL_FILE}: ${DIST_DIR} ${CACHE_DIR}
	@@echo "Zip ${REL_FILE}"
	@@zip -r -q ${REL_FILE} ${CACHE_DIR}

${CACHE_DIR}: ${LIB_DIR}/webim.class.php
	@@echo "Create cache directory"
	@@mkdir -p ${CACHE_DIR}
	@@echo "Copy source"
	@@cp -r ${SRC_FILES} ${CACHE_DIR}
	@@rm -rf ${CACHE_DIR}/lib/.git
	@@cp -r ${CONFIG_FILE} ${CACHE_DIR}/discuz_plugin_webim_SC_UTF8.xml
	@@echo "Change version"
	@@cat ${SRC_DIR}/config.php | sed s/@VERSION/${VERSION}/ > ${CACHE_DIR}/config.php
	@@cat ${SRC_DIR}/discuz_plugin_webim.xml | sed s/@VERSION/${VERSION}/ > ${CACHE_DIR}/discuz_plugin_webim.xml
	@@echo "Convert charset"
	@@iconv -f UTF-8 -t GBK ${CONFIG_FILE} > ${CACHE_DIR}/discuz_plugin_webim_SC_GBK.xml
	@@iconv -f GBK -t BIG5 ${CACHE_DIR}/discuz_plugin_webim_SC_GBK.xml > ${CACHE_DIR}/discuz_plugin_webim_TC_BIG5.xml
	@@iconv -f BIG5 -t UTF-8 ${CACHE_DIR}/discuz_plugin_webim_TC_BIG5.xml > ${CACHE_DIR}/discuz_plugin_webim_TC_UTF8.xml

${DIST_DIR}:
	@@echo "Create distribution directory"
	@@mkdir -p ${DIST_DIR}
	@@echo "	"${DIST_DIR}

${LIB_DIR}/webim.class.php:
	@@git submodule update --init ui

clean:
	@@echo "Remove release cache and dist directory"
	@@rm -rf ${DIST_DIR}
	@@rm -rf ${CACHE_DIR}
	@@echo "	"${DIST_DIR}
	@@echo "	"${CACHE_DIR}

