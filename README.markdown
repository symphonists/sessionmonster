# Session Monster

## Installation
1. Upload `sessionmonster`, to your `/extensions` folder
2. Navigate to 'System' > 'Extensions' and enable it
3. Add the "Session Monster: Add GET variable to Session" Event to your page. Documentation on the Event can be viewed via the Components area of the Symphony Admin.

## Usage
After attaching the event to a page, request it with a URL such as:

	/my-page/?colour=red&shape=square

This will store these two values into the Session Monster session. To unset session values, send an empty string:

	/my-page/?shape=

Values added to the session via session monster will show in the page params like so:

	$sessionmonster-colour = red
	$sessionmonster-shape = square

And in the page XML as:

	<data>
		<params>
			...
			<sessionmonster-colour>red</sessionmonster-colour>
			<sessionmonster-shape>square</sessionmonster-shape>
		</params>
		...
	</data>