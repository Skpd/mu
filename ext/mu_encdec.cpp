#include <string>
#include <iostream>

#include <phpcpp.h>
#include "Decoder.h"

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

	unsigned char* dst = new unsigned char[(src[1] - 2) * 8 / 11];

	int decLength = DecryptC3asServer(dst, src+2, src[1] - 2);

	DecXor32(dst, 1, decLength);

	dst[0] = src[0];
	dst[1] = decLength;

	if (dst[0] == 0xC3 && dst[2] == 0x01) {
		EncDecLogin(dst + 3, 10);
		EncDecLogin(dst + 13, 10);
	}

    std::string result(dst, dst + decLength / sizeof dst[0]);

    Php::Value r(result);

    return r;
}

extern "C"
{
    PHPCPP_EXPORT void *get_module()
    {
        static Php::Extension extension("mu","0.1");

        extension.add("mu_decode_c3", mu_decode_c3, {
            Php::ByVal("string", Php::Type::String)
        });

        return extension.module();
    }
}