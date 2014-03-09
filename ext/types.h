typedef unsigned int DWORD;
typedef unsigned char BYTE;
typedef int BOOL;
typedef unsigned short WORD;
typedef char CHAR;
typedef unsigned char UCHAR;
typedef const CHAR *LPSTR;
typedef BYTE *PBYTE;
typedef BYTE *LPBYTE;
typedef void VOID;

#ifndef FALSE
#define FALSE (0)
#endif
#ifndef TRUE
#define TRUE (!FALSE)
#endif

#define ZeroMemory(Destination,Length) memset((Destination),0,(Length))
//#define TRUE 1;
//#define FALSE 0;
//#define VOID void;
