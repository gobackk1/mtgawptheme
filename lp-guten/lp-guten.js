wp.domReady(function() {
  // カバーブロック
  wp.blocks.registerBlockStyle('core/cover', {
    name: 'lpheight',
    label: '高さをアレンジ'
  });
  // 見出しブロック
  wp.blocks.registerBlockStyle('core/heading', {
    name: 'lpshadow',
    label: '影をつける'
  });
  wp.blocks.registerBlockStyle('core/paragraph', {
    name: 'lpshadow',
    label: '影をつける'
  });
  wp.blocks.registerBlockStyle('core/columns', {
    name: 'lpconcepts',
    label: 'コンセプト'
  });
});