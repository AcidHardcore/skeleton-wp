wp.domReady(() => {

	/**
	 * Default Headings classes
	 */
	wp.blocks.registerBlockStyle('core/heading', [
		{
			name: 'default',
			label: 'Default',
			isDefault: true,
		},
		{
			name: 'title',
			label: 'Title',
		}
	]);

	// wp.blocks.registerBlockStyle('core/paragraph', [
	// 	{
	// 		name: 'default',
	// 		label: 'Default',
	// 		isDefault: true,
	// 	},
	// 	{
	// 		name: 'block',
	// 		label: 'Block',
	// 	}
	// ]);

	/**
	 * Unregister block type
	 */
	// wp.blocks.unregisterBlockType( 'core/media-text' );
	// wp.blocks.unregisterBlockType( 'core/search' );
});
