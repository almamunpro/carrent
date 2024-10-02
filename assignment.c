// Nigar Sultana
// 201071059
// batch-27th


#include <stdio.h>
#include <string.h>

void encrypt(char *plainText, int key) {
    int i;
    char ch;
    for(i = 0; plainText[i] != '\0'; ++i) {
        ch = plainText[i];

        if(ch >= 'a' && ch <= 'z') {
            ch = (ch - 'a' + key) % 26 + 'a';
        }
        else if(ch >= 'A' && ch <= 'Z') {
            ch = (ch - 'A' + key) % 26 + 'A';
        }
        
        plainText[i] = ch;
    }
}

void decrypt(char *cipherText, int key) {
    int i;
    char ch;
    for(i = 0; cipherText[i] != '\0'; ++i) {
        ch = cipherText[i];

        // Decrypt lowercase letters
        if(ch >= 'a' && ch <= 'z') {
            ch = (ch - 'a' - key + 26) % 26 + 'a';
        }
        // Decrypt uppercase letters
        else if(ch >= 'A' && ch <= 'Z') {
            ch = (ch - 'A' - key + 26) % 26 + 'A';
        }
        
        cipherText[i] = ch;
    }
}

int main() {
    char plainText[100], cipherText[100];
    int key;

    printf("Enter a plain text: ");
    gets(plainText); 
    printf("Enter key: ");
    scanf("%d", &key);

    strcpy(cipherText, plainText);

    encrypt(cipherText, key);
    printf("Encrypted Text: %s\n", cipherText);

    decrypt(cipherText, key);
    printf("Decrypted Text: %s\n", cipherText);

    return 0;
}
