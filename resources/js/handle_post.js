import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header'; 
import List from '@editorjs/list'; 
import Checklist from '@editorjs/checklist';
import Embed from '@editorjs/embed';
import ImageTool from '@editorjs/image';
import Quote from '@editorjs/quote';
import Raw from '@editorjs/raw';
import SimpleImage from '@editorjs/simple-image';
import LinkTool from '@editorjs/link';
let meta = document.head.querySelector('meta[name="csrf-token"]');

const editor = new EditorJS({
  holder: 'editorjs',
  tools: {
    header: {
      class: Header,
      inlineToolbar: ['link']
    },
    list: {
      class: List,
      inlineToolbar: true
    },
    checklist: {
      class: Checklist,
      inlineToolbar: true
    },
    embed: {
      class: Embed,
      inlineToolbar: false
    },
    image: {
      class: ImageTool,
      config: {
        endpoints: {
          byFile: '/uploadFile',
          // byUrl: '/uploadFile', // Chỉ cần một endpoint, Laravel sẽ tự động xử lý
        },
        additionalRequestData: {
          '_token': meta.content, // Laravel sẽ nhận đúng CSRF token
        },
      }
    },
    quote: {
      class: Quote,
      inlineToolbar: true,
      config: {
        quotePlaceholder: 'Enter a quote',
        captionPlaceholder: 'Quote author',
      },
    },
    raw: Raw,
    simpleImage: SimpleImage,
    linkTool: {
      class: LinkTool,
      config: {
        endpoint: '/fetchUrl',
      }
    },
  },
});
window.editor = editor;