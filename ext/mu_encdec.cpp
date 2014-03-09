#include <string>
#include <iostream>

#include <phpcpp.h>
#include "Decoder.h"
#include "types.h"

using namespace std;

Php::Value mu_decode_c3(Php::Parameters &params)
{
    if (params.size() < 1) {
        return false;
    }

    unsigned char *src = new unsigned char[params[0].size()];

    for (int i=0; i<params[0].size(); i++) {
        src[i] = (int) params[0][i];
    }

	unsigned char* dst = new unsigned char[512];

    int size = 0;
    BYTE headcode, xcode = 0, subhead;

    if ((int) src[0] == 0xC1 || (int) src[0] == 0xC3) {
        UCHAR * pBuf;
        pBuf		= &src[0];
        size		= pBuf[1];
        headcode	= pBuf[2];
        xcode		= src[0];
    }

    if ( xcode == 0xC3 )
    {
        int decLength = DecryptC3asServer(dst, src+2, src[1] - 2);

        headcode		= dst[1];

        DecXor32(dst + 1, 2, decLength - 1);

        subhead	        = dst[2];
        dst[1]		    = (decLength&0xFF)+2;

//        printf(
//            "headcode: %02X\nsubhead: %02X\nxcode: %02X\ndec length: %u\n",
//            headcode, subhead, xcode, decLength
//        );

        dst[0] = xcode;
        dst[2] = headcode;

        if (headcode == 0xF1 && subhead == 0x01) {
            EncDecLogin(dst + 3, 10);
            EncDecLogin(dst + 13, 10);
        }

        params[1] = (int) xcode;
        params[2] = (int) headcode;
        params[3] = (int) subhead;

        std::string result(dst, dst + decLength / sizeof dst[0]);
        Php::Value r(result);
        return r;
    } else {
        std::string result(src, src + size / sizeof src[0]);
        Php::Value r(result);
        return r;
    }
}

extern "C"
{
    PHPCPP_EXPORT void *get_module()
    {
        static Php::Extension extension("mu","0.1");

        extension.add("mu_decode_c3", mu_decode_c3, {
            Php::ByVal("data", Php::Type::String),
            Php::ByRef("class", Php::Type::Numeric),
            Php::ByRef("head", Php::Type::Numeric),
            Php::ByRef("sub", Php::Type::Numeric)
        });

        return extension.module();
    }
}