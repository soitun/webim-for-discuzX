#Release webim

PRIFIX= .
SRC_DIR= ${PRIFIX}
DIST_DIR= ${PRIFIX}/dist
LIB_DIR= ${PRIFIX}/lib
VERSION= 0.1.0
PRODUCT_NAME= discuzX
CACHE_DIR=${PRIFIX}/webim
REL_FILE = ${DIST_DIR}/WebIM_For_${PRODUCT_NAME}-${VERSION}.zip

SRC_FILES = ${SRC_DIR}/*.php \
	    ${SRC_DIR}/*.xml \
	    ${SRC_DIR}/*.md \
	    ${SRC_DIR}/lib \
	    ${SRC_DIR}/static \
	    ${SRC_DIR}/template \

all: ${REL_FILE}
	@@echo "Build complete."

${REL_FILE}: ${DIST_DIR} ${CACHE_DIR}
	@@echo "Zip ${REL_FILE}"
	@@zip -r -q ${REL_FILE} ${CACHE_DIR}

${CACHE_DIR}: ${LIB_DIR}
	@@echo "Create cache directory"
	@@mkdir -p ${CACHE_DIR}
	@@echo "Copy source"
	@@cp -r ${SRC_FILES} ${CACHE_DIR}
	@@rm -rf ${CACHE_DIR}/lib/.git
	@@echo "Change version"
	@@cat ${SRC_DIR}/config.php | sed s/@VERSION/${VERSION}/ > ${CACHE_DIR}/config.php
	@@cat ${SRC_DIR}/discuz_plugin_webim.xml | sed s/@VERSION/${VERSION}/ > ${CACHE_DIR}/discuz_plugin_webim.xml

${DIST_DIR}:
	@@echo "Create distribution directory"
	@@mkdir -p ${DIST_DIR}
	@@echo "	"${DIST_DIR}

${LIB_DIR}:
	@@git submodule update --init ui

clean:
	@@echo "Remove release cache and dist directory"
	@@rm -rf ${DIST_DIR}
	@@rm -rf ${CACHE_DIR}
	@@echo "	"${DIST_DIR}
	@@echo "	"${CACHE_DIR}

