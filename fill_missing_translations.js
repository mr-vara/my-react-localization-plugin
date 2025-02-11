const fs = require('fs');
const axios = require('axios');
const gettextParser = require('gettext-parser');

const languages = ['fr_FR', 'es_ES', 'de_DE'];
const potFile = fs.readFileSync('languages/my-react-localization-plugin.pot');
const pot = gettextParser.po.parse(potFile);

languages.forEach(async (lang) => {
  const poPath = `languages/my-react-localization-plugin-${lang}.po`;
  let poFile = fs.readFileSync(poPath);
  let po = gettextParser.po.parse(poFile);

  for (let msgid in pot.translations['']) {
    if (msgid && (!po.translations[''][msgid] || !po.translations[''][msgid].msgstr[0])) {
      const textToTranslate = msgid;
      try {
        const response = await axios.post('https://libretranslate.com/translate', {
          q: textToTranslate,
          source: 'en',
          target: lang.split('_')[0],
          format: 'text'
        });

        po.translations[''][msgid] = po.translations[''][msgid] || { msgid: msgid, msgstr: [''] };
        po.translations[''][msgid].msgstr[0] = response.data.translatedText;
      } catch (error) {
        console.error(`Translation failed for ${msgid}:`, error.message);
      }
    }
  }

  const updatedPo = gettextParser.po.compile(po);
  fs.writeFileSync(poPath, updatedPo);
});

