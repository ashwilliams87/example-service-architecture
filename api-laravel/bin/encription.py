from Crypto.Cipher import AES
import binascii
import sys


BS = AES.block_size
#pad = lambda s: s + (BS - len(s) % BS) * (chr(BS - len(s) % BS).encode)
unpad = lambda s : s[0:-ord(s[-1])]


def pad(txt):
    return txt + (BS - len(txt) % BS) * str.encode(chr(BS - len(txt) % BS))

if 4 != len(sys.argv):
    raise Exception('error number of arguments')

args = sys.argv
# key = os.urandom(16) # the length can be (16, 24, 32)
key = str.encode(args[1])
file_in = args[2]
file_out = args[3]
cipher = AES.new(key,AES.MODE_ECB)
f = open(file_out,'wb')
txt = open(file_in, 'rb')
txt = txt.read()
f.write(cipher.encrypt(pad(txt)))
f.close()