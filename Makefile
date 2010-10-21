#Release webim

PRIFIX= .
SRC_DIR= ${PRIFIX}
DIST_DIR= ${PRIFIX}/dist
LIB_DIR= ${PRIFIX}/lib
VERSION= 1.2.1
PRODUCT_NAME= DiscuzX
CACHE_DIR= ${PRIFIX}/webim
REL_FILE = ${DIST_DIR}/WebIM_For_${PRODUCT_NAME}-${VERSION}.zip
LATE_REL_FILE = ${DIST_DIR}/WebIM_For_${PRODUCT_NAME}-latest.zip
CONFIG_FILE= ${SRC_DIR}/discuz_plugin_webim.xml
REPLACE_VER= sed s/@VERSION/${VERSION}/

SRC_FILES = ${SRC_DIR}/*.php \
	    ${SRC_DIR}/*.md \
	    ${SRC_DIR}/*.txt \
	    ${SRC_DIR}/*.js \
	    ${SRC_DIR}/lib \
	    ${SRC_DIR}/static \

all: ${REL_FILE}
	@@echo "Build complete."

${REL_FILE}: ${DIST_DIR} ${CACHE_DIR}
	@@echo "Zip ${REL_FILE}"
	@@zip -r -q ${REL_FILE} ${CACHE_DIR}
	@@cp ${REL_FILE} ${LATE_REL_FILE}

${CACHE_DIR}: ${LIB_DIR}/webim.class.php
	@@echo "Create cache directory"
	@@mkdir -p ${CACHE_DIR}
	@@echo "Copy source"
	@@cp -r ${SRC_FILES} ${CACHE_DIR}
	@@rm -rf ${CACHE_DIR}/lib/.git
	@@echo "Change version"
	@@cat ${SRC_DIR}/config.php | ${REPLACE_VER} > ${CACHE_DIR}/config.php
	@@cat ${SRC_DIR}/common.php | ${REPLACE_VER} > ${CACHE_DIR}/common.php
	@@echo "Convert charset"
	@@cat ${CONFIG_FILE} | ${REPLACE_VER} > ${CACHE_DIR}/discuz_plugin_webim_SC_UTF8.xml
	@@iconv -f UTF-8 -t GBK ${CONFIG_FILE} | ${REPLACE_VER} > ${CACHE_DIR}/discuz_plugin_webim_SC_GBK.xml
	@@iconv -f UTF-8 -t GB2312 ${CONFIG_FILE} | ${REPLACE_VER} | iconv -f GB2312 -t BIG5 > ${CACHE_DIR}/discuz_plugin_webim_TC_BIG5.xml
	@@iconv -f UTF-8 -t GB2312 ${CONFIG_FILE} | ${REPLACE_VER} | iconv -f GB2312 -t BIG5 | iconv -f BIG5 -t UTF-8 > ${CACHE_DIR}/discuz_plugin_webim_TC_UTF8.xml

${DIST_DIR}:
	@@echo "Create distribution directory"
	@@mkdir -p ${DIST_DIR}
	@@echo "	"${DIST_DIR}

${LIB_DIR}/webim.class.php:
	@@git submodule update --init lib

clean:
	@@echo "Remove release cache and dist directory"
	@@rm -rf ${DIST_DIR}
	@@rm -rf ${CACHE_DIR}
	@@echo "	"${DIST_DIR}
	@@echo "	"${CACHE_DIR}

