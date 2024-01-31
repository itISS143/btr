from googletrans import Translator

def translate_text_to_japanese(text):
    translator = Translator()
    translated_text = translator.translate(text, dest='ja')
    return translated_text.text

if __name__ == "__main__":
    text = input("Enter text to translate to Japanese: ")
    translated_text = translate_text_to_japanese(text)
    print("Translated text:", translated_text)
