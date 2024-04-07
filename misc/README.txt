Hi!  Welcome to gizmos.

ToDo 14 July:
	Fix color matching for trigger_builds triggering
		This might also be a problem for picks
		Update the material building script to convert trigger_color comma list into array
		
	Compress img assets more
	
	New game to test victory points
		Add VP indicator in player card
		
	UI breakpoints for making bigger screens look nicer

ToDo:
	Converters:
		Lots of if elses to determine what a converter does
		Push COLORs to spend into array
		PROBLEM: longer chains of gizmos are hard to validate.  Working backwords from card cost is unintuitive, but works
			Solution: when selecting converters in the "intuitive" order, confirm validity by searching out the requisite converters
		Next steps: server-side updates to the build function to confirm converters valid
			
	End game condition (complete round):
		4th level 3
		OR 16th total (including start)
	Statistics
	UI improvements:
		Sliding to correct locations
	
	

A few things about the code I feel obligated to point out:

	1) I changed my mind (or forgot) several times about nomenclature around certain game items.  Thus in reading the code, assume that the following groups of items may be used interchangeably:
		a) Gizmo === Card --- Notably I try to refer to objects from the database as "card" and objects from materials as "gizmo" or "mt_gizmo"
		b) Sphere === Token === Energy
		c) File === Archive
		d) Pick === Select
		e) Buy === Purchase
		
	2) See dbmodel.sql for details around usage of built-in deck columns (should be fairly easy to intuit them)
	
	3) I try too hard to be creative writing JS which overcomplicates the code in some cases
	
	4) I'm scared to delete code because I might need it later which leads to horridly unnecessary comment blocks