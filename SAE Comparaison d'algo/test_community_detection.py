amis = ["Alice", "Bob", "Alice", "Charlie", "Bob", "Denis"]

p_amis=['Muriel','Yasmine','Muriel','Joël','Yasmine','Joël','Yasmine','Thomas','Thomas','Daria','Thomas','Carole',
       'Joël','Nassim','Joël','Andrea','Joël','Ali','Nassim','Andrea','Nassim','Ali','Andrea','Ali','Andrea',
        'Valentin','Léo','Valentin','Léo','Thierry','Léo','Axel','Thierry','Axel']

reseau = ["Alice", "Bob","Alice","Dominique", "Bob","Charlie", "Bob","Dominique"]

def test_create_network():
    assert create_network(amis)=={'Alice': ['Bob', 'Charlie'], 'Bob': ['Alice', 'Denis'], 'Charlie': ['Alice'], 'Denis': ['Bob']}
    assert create_network(p_amis)=={'Muriel': ['Yasmine', 'Joël'], 'Yasmine': ['Muriel', 'Joël', 'Thomas'], 'Joël': ['Muriel', 'Yasmine', 'Nassim', 'Andrea', 'Ali'], 'Thomas': ['Yasmine', 'Daria', 'Carole'], 'Daria': ['Thomas'], 'Carole': ['Thomas'], 'Nassim': ['Joël', 'Andrea', 'Ali'], 'Andrea': ['Joël', 'Nassim', 'Ali', 'Valentin'], 'Ali': ['Joël', 'Nassim', 'Andrea'], 'Valentin': ['Andrea', 'Léo'], 'Léo': ['Valentin', 'Thierry', 'Axel'], 'Thierry': ['Léo', 'Axel'], 'Axel': ['Léo', 'Thierry']}
    assert create_network(reseau)=={"Alice" : ["Bob", "Dominique"], "Bob" : ["Alice", "Charlie", "Dominique"], "Charlie" : ["Bob"], "Dominique" : ["Alice", "Bob"]}
    print("Test de la fonction OK")
    
test_create_network()

def test_get_people():
    assert get_people(create_network(amis))==['Alice', 'Bob', 'Charlie', 'Denis']
    assert get_people(create_network(p_amis))==['Muriel', 'Yasmine', 'Joël', 'Thomas', 'Daria', 'Carole', 'Nassim', 'Andrea', 'Ali', 'Valentin', 'Léo', 'Thierry', 'Axel']
    assert get_people(create_network(reseau))==['Alice', 'Bob', 'Dominique', 'Charlie']
    print('Test de la fonction OK')
    
test_get_people()

def test_are_friends():
    assert are_friends(create_network(p_amis), "Alice", "Bob")==True
    assert are_friends(create_network(p_amis), "Thomas", "Léa")==False
    assert are_friends(create_network(amis), "Alice","Charlie")==True
    assert are_friends(create_network(amis), "Denis", "Charlie")==False
    assert are_friends(create_network(reseau), "Dominique","Bob")==True
    assert are_friends(create_network(reseau), "Alice","Charlie")==False
    print("Test de la fonction OK")
    
test_are_friends()    

def test_all_his_friends():
    assert all_his_friends(create_network(amis),'Charlie',['Alice'])==True
    assert all_his_friends(create_network(amis),'Bob',['Alice', 'Charlie'])==False
    assert all_his_friends(create_network(p_amis),'Daria', ['Thomas'])==True
    assert all_his_friends(create_network(p_amis),'Yasmine', ['Muriel', 'Nassim', 'Thomas'])==False
    assert all_his_friends(create_network(reseau), "Dominique" ,["Alice", "Bob"])==True
    assert all_his_friends(create_network(reseau), "Alice" , ["Bob", "Charlie"])==False
    print("Test de la fonction OK")
    
test_all_his_friends()

def test_is_a_community():
    assert is_a_community(create_network(p_amis),['Yasmine', 'Joël'])==True
    assert is_a_community(create_network(p_amis),['Yasmine', 'Thomas', 'Carole'])==False
    assert is_a_community(create_network(amis),['Alice','Bob'])==True
    assert is_a_community(create_network(amis),['Charlie','Denis'])==False
    assert is_a_community(create_network(reseau),["Alice", "Bob", "Dominique"])==True
    assert is_a_community(create_network(reseau), ["Alice", "Bob", "Charlie"])==False
    print("Test de la fonction OK")
    
test_is_a_community()

def test_find_community():
    assert find_community(create_network(reseau),["Alice", "Bob", "Charlie", "Dominique"])==['Alice', 'Bob', 'Dominique']
    assert find_community(create_network(reseau),["Charlie", "Alice", "Bob", "Dominique"])==["Charlie", "Bob"]
    assert find_community(create_network(reseau),["Charlie", "Alice", "Dominique"])==["Charlie"]
    print("Test de la fonction OK")
    
def test_find_community():
    assert find_community(create_network(friends),["Alice", "Bob", "Charlie", "Dominique"])==['Alice', 'Bob', 'Dominique']
    assert find_community(create_network(friends),["Charlie", "Alice", "Bob", "Dominique"])==["Charlie", "Bob"]
    assert find_community(create_network(friends),["Charlie", "Alice", "Dominique"])==["Charlie"]
    print("Test de la fonction OK")
    
test_find_community()

def test_order_by_decreasing_popularity():
    assert order_by_decreasing_popularity(create_network(friends), ["Alice", "Bob", "Charlie"])==["Bob", "Alice", "Charlie"]
    assert order_by_decreasing_popularity(create_network(p_amis), ['Muriel', 'Joël', 'Thomas'])==["Joël","Thomas","Muriel"]
    assert order_by_decreasing_popularity(create_network(amis), ['Bob', 'Charlie'])==["Bob","Charlie"]
    print("Test de la fonction OK")
    
test_order_by_decreasing_popularity()

def test_find_community_by_decreasing_popularity():
    assert find_community_by_decreasing_popularity(create_network(friends))==["Bob", "Alice", "Dominique"]
    assert find_community_by_decreasing_popularity(create_network(amis))==['Alice', 'Bob']
    assert find_community_by_decreasing_popularity(create_network(p_amis))==['Joël', 'Yasmine', 'Muriel']
    print("Test de la fonction OK")
    
test_find_community_by_decreasing_popularity()

def test_find_community_from_person():
    assert find_community_from_person(create_network(friends),"Alice")==["Alice", "Bob", "Dominique"]
    assert find_community_from_person(create_network(friends),"Charlie")==["Charlie","Bob"]
    assert find_community_from_person(create_network(p_amis),"Thierry")==["Thierry", "Léo","Axel"]
    assert find_community_from_person(create_network(amis),"Charlie")==["Charlie","Alice"]
    print("Test de la fonction OK")
    
test_find_community_from_person()

def test_find_max_community():
    assert find_max_community(create_network(friends))==["Alice", "Bob", "Dominique"]
    assert find_max_community(create_network(p_amis))==['Nassim', 'Joël', 'Andrea', 'Ali']
    assert find_max_community(create_network(amis))==["Alice", "Bob"]
    print("Test de la fonction OK")
    
test_find_max_community()