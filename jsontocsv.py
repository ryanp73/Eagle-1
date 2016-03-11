import json

# def createLine(data):
# 	line = str(data['match_number']) + ','
# 	line += str(data['alliances']['blue']['teams'][0])[3:] + ','
# 	line += str(data['alliances']['blue']['teams'][1])[3:] + ','
# 	line += str(data['alliances']['blue']['teams'][2])[3:] + ','
# 	line += str(data['alliances']['red']['teams'][0])[3:] + ','
# 	line += str(data['alliances']['red']['teams'][1])[3:] + ','
# 	line += str(data['alliances']['red']['teams'][2])[3:] + '\n'
# 	return line

# with open('./data/2016mokc/matches.json', 'r') as file:
# 	data = json.loads(file.readline())
	
# 	newf = open('./matches.csv', 'w')
# 	newf.write('Match,Blue1,Blue2,Blue3,Red1,Red2,Red3\n')

# 	for match in data:
# 		print(match)
# 		newf.write(createLine(match))
# 	newf.close()

with open('./data/2016mokc/teams.json', 'r') as file:
	data = json.loads(file.readline())
	newf = open('./teams.csv', 'w')
	newf.write('Number\n')
	for team in data:
		newf.write(str(team['team_number']) + '\n')
	newf.close()