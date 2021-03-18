#Created by Hamza Slaoui Habib
#Program that simulates the sudoku game and finds a solution for it
#objectives: Learn and apply Backtracking Algorithm, as well as pygame


#Algorithm to solve sudoku
# 1 - Find empty spot(in this case a 0)
# 2- Fill it with a nummber (1-9)
# 3- Check if the number is valid in that specific spot (according to the rules of sudoku)
# 4- a: if the number is valid, recursively attempt to fill the board the previous steps
#	 b: else, reset it (make it 0), and go back to the previous step

#Part I: simple board (no GUI)

#Choosing a solvable sudoku board (pick any from internet)
board= [
	[7,8,0,4,0,0,1,2,0],
	[6,0,0,0,7,5,0,0,9],
	[0,0,0,6,0,1,0,7,8],
	[0,0,7,0,4,0,2,6,0],
	[0,0,1,0,5,0,9,3,0],
	[9,0,4,0,6,0,0,0,5],
	[0,7,0,3,0,0,0,1,2],
	[1,2,0,0,0,7,4,0,0],
	[0,4,9,2,0,6,0,0,7]
]

#Printing the board first for an before after image (not necessary)
#board includes the numbers as well as the separators to form 9 boxes
def print_board(brd):
	for i in range(len(board)): #Placing separator for Rows
		if i% 3 == 0 and i!=0:
			print("- - - - - - - - - - - - - ")
		for j in range (len(board[i])):
			if j %3 == 0 and j!=0: #Placing separator for Columns
				print (" | ", end="")
			if j!=8:
				print(str(brd[i][j])+ " ", end="") #Printing the result with the correct formatting
			else:
				print(brd[i][j]) #ading the last results that couldn't be printed because of the formatting

#function for the step 1: finding an empty spot
def find_spot(brd):

	for i in range(len(brd)): #Looping through the board
		for j in range(len(brd[0])):
			if brd[i][j]== 0: #finding the very first empty spot
				return (i,j) #NB: we're returning y,x
	return None #Means we didn't find any zero (board full) 


#function for step 3
#Checking if the number we plugged in is valid
#Verification done by row, column, then box
def valid_spot(brd, number, position):

	#Checking the row
	#To check the row, we need to loop through all the columns, but we will not be checking the position where we just inserted the number (second part of the loop)

	for i in range(len(brd[0])): #Looping through all the columns
		if brd[position[0]][i] == number and position[1] != i: #discarding the position we just plugged in. Since position is sent in the form of [0][1], we're making sure that we don't verify where we plug
			return False #Same instance of the  number in a row, hence it's false

	#Checking the Column
	#Same logic as for rows but in reverse
	for i in range(len(brd)):
		if brd[i][position[1]] == number and position[0] != i:
			return False #Same instance of the number in a column

	#Checking the Boxes
	#First, determining with boxes we are in
	#we just need to know the box not the exact position,
	#so for 0,1,2 / 3,4,5/ 6,7,8 all of these have the same result with //3 which is enough as an information
	#Keep in mind that it is reversed because of the way we sent the output in the function find_spot().
	box_x = position[1] //3
	box_y = position[0] //3

	for i in range(box_y*3, box_y*3 +3): #Multiplying by 3 so that we get to the right box (0*3 / 1*3 / 2*3) even if we loop outside the range it's fine, it will automatically stop
		for j in range(box_x*3, box_x*3+3):
			if brd[i][j]== number and (i,j) != position:
				return False #Same instance of the number in the box hence, false


	return True #Since the number passed all verifications, it means it's correct


#Main function => step 4. Here is where the recursive will work 
#	Function for Step 2 is also inside
def solve_sudoku(brd):
	find = find_spot(brd)
	#Best case scenario as in if they are no empty spot => Board is full => end of game
	if not find:
		return True
	else:
		row, col = find #saving the position of the empty spot in two variables (y,x) => (row, col) (yes it's reversed)
 
	#Verify if it's valid and if the game is complete 
	for i in range(1,10): #Looping through all 9 numbers
		if valid_spot(brd, i, (row,col)):
			brd[row][col] = i
		#This where the recursion happens:
			if solve_sudoku(brd):
				return True
		#Backtracking by putting 0 in it and coming back and trying another number
			brd[row][col]=0


	return False

print("\t Board given: ")
print_board(board)
solve_sudoku(board)
print("\t Solution:")
print_board(board)



