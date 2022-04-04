wp.domReady(() => {

	/**
	 * Default Headings classes
	 */
	wp.blocks.registerBlockStyle('core/heading', [
		{
			name: 'title',
			label: 'Title',
			isDefault: true,
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

	wp.blocks.unregisterBlockStyle(
		'core/button',
		['default', 'outline', 'squared', 'fill']
	);

	wp.blocks.registerBlockStyle(
		'core/button',
		[
			{
				name: 'default',
				label: 'Default',
				isDefault: true,
			},
			{
				name: 'outlined',
				label: 'Outlined',
			}
		]
	);


	/**
	 * Unregister block type
	 */
	// wp.blocks.unregisterBlockType( 'core/media-text' );
	// wp.blocks.unregisterBlockType( 'core/search' );
});
