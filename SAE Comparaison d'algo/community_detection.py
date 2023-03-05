##############
# SAE S01.01 #
##############

def liste_amis(amis, prenom):
    """
        Retourne la liste des amis de prenom en fonction du tableau amis.

    """
    prenoms_amis = []
    i = 0
    while i < len(amis)//2:
        if amis[2 * i] == prenom:
            prenoms_amis.append(amis[2*i+1])
        elif amis[2*i+1] == prenom:
            prenoms_amis.append(amis[2*i])
        i += 1
    return prenoms_amis

def nb_amis(amis, prenom):
    """ Retourne le nombre d'amis de prenom en fonction du tableau amis. """
    return len(liste_amis(amis, prenom))


def personnes_reseau(amis):
    """ Retourne un tableau contenant la liste des personnes du réseau."""
    people = []
    i = 0
    while i < len(amis):
        if amis[i] not in people:
            people.append(amis[i])
        i += 1
    return people

def taille_reseau(amis):
    """ Retourne le nombre de personnes du réseau."""
    return len(personnes_reseau(amis))

def lecture_reseau(path):
    """ Retourne le tableau d'amis en fonction des informations contenues dans 
    le fichier path."""
    f = open(path, "r")
    l = f.readlines()
    f.close()
    amis = []
    i = 0
    while i < len(l):
        fr = l[i].split(";")
        amis.append(fr[0].strip())
        amis.append(fr[1].strip())
        i += 1
    return amis

def dico_reseau(amis):
    """ Retourne le dictionnaire correspondant au réseau."""
    dico = {}
    people = personnes_reseau(amis)
    i = 0
    while i < len(people):
        dico[people[i]] = liste_amis(amis, people[i])
        i += 1
    return dico

def nb_amis_plus_pop (dico_reseau):
    """ Retourne le nombre d'amis des personnes ayant le plus d'amis."""
    personnes = list(dico_reseau)
    maxi = len(dico_reseau[personnes[0]])
    i = 1
    while i < len(personnes):
        if maxi < len(dico_reseau[personnes[i]]):
            maxi = len(dico_reseau[personnes[i]])
        i += 1
    return maxi


def les_plus_pop (dico_reseau):
    """ Retourne les personnes les plus populaires, c'est-à-dire ayant le plus 
    d'amis."""
    max_amis = nb_amis_plus_pop(dico_reseau)
    most_pop = []
    personnes = list(dico_reseau)
    i = 1
    while i < len(personnes):
        if len(dico_reseau[personnes[i]]) == max_amis:
            most_pop.append(personnes[i])
        i += 1
    return most_pop

##############
# SAE S01.02 #
##############

def create_network(list_of_friends):
    """
         Paramètres:
    list_of_friends : TYPE tableau
        DESCRIPTION : liste d'amis

    Retourne le dictionnaire correspondant au réseau associé au tableau de 
    couples d'amis
    """
    i = 0
    dico = {}
    tab = []

    while i < len(list_of_friends): 
        if not list_of_friends[i] in tab:
            tab.append(list_of_friends[i])
            if i%2==0:
                dico[list_of_friends[i]] = [list_of_friends[i+1]]
            else:
                dico[list_of_friends[i]] = [list_of_friends[i-1]]
        else:
            if i%2==0:
                dico[list_of_friends[i]].append(list_of_friends[i+1])
            else:
                dico[list_of_friends[i]].append(list_of_friends[i-1])
        i+=1
    return(dico)


def get_people(network):
    """
        Paramètres
    network : TYPE dictionnaire
    description : réseau d'amis

    Retourne une liste des personnes de ce réseau dans un tableau 'tab'
    """
    tab = list(network)
    return tab 


def are_friends(network, person1, person2):
    """
        Paramètres
    network : TYPE dictionnaire
        description: Réseau d'amis
    person1 : TYPE chaîne de caractère
        description: Personne
    person2 : TYPE chaîne de caractère
        description: Personne

    Retourne 'True' si les deux personnes sont amies et 'False' sinon
    """
    clefs = list(network.keys())
    i = 0
    while i < len(network):
        if clefs[i] == person1:
            if not person2 in network[clefs[i]]:
                return False
        elif clefs[i] == person2:
            if not person1 in network[clefs[i]]:
                return False
        i+=1
    return True
    

def all_his_friends(network, person, group):
    """
        Paramètres
    network : TYPE dictionnaire
    description : réseau d'amis
    person : TYPE chaîne de caractère
    description : personne faisant partie du réseau
    group : TYPE tableau
        description: groupe de personne sous forme de liste
    
    Retourne 'True' si la personne est amie avec toutes les personnes du groupe
    et 'False' sinon
    """
    clefs = list(network.keys())
    i = 0
    j = 0
    while i < len(network):
        if clefs[i] == person:
            while j < len(group):
                if not group[j] in network[clefs[i]]:
                    return False
                j+=1
        i+=1
    return True


def is_a_community(network, group):
    """
    Paramètres
    network : TYPE dictionnaire
        description: réseau d'amis
    group : TYPE tableau
        description: groupe de personne sous forme de liste

    Retourne True si ce groupe est une communauté, et False sinon
    """
    i = 0
    tab = []
    while i < len(group):
        tab.append(group[i])
        group.pop(i)
        if all_his_friends(network, tab[0], group) == False:
            return False
        group.insert(i, tab[0])
        tab = []
        i+=1
    return True


def find_community(network, group):
    """
    Paramètres
    network : TYPE dictionnaire
        description: réseau d'amis
    group : TYPE tableau
        description: groupe de personne sous forme de liste

    Retourne une communauté en fonction de l'heuristique décrite
    """
    new_group = []
    new_group.append(group[0])
    i = 0
    while i < len(group)-1:
        if all_his_friends(network, group[i+1], new_group) == True:
            new_group.append(group[i+1])
        i+=1
    return(new_group)


def order_by_decreasing_popularity(network, group):
    """
        Paramètres
    network : TYPE dictionnaire
        description: réseau d'amis
    group : TYPE tableau
        description: groupe de personne sous forme de liste

    Retourne un tableau trié du groupe de personnes selon la popularité 
    (nombre d'amis) décroissante.
    """
    i = 0
    while i < len(group): #Tri le groupe selon la popularité des personnes
        j = i
        while j < len(group)-1 and len(network[group[j]]) < len(network[group[j+1]]):
            c = group[j]
            group[j] = group[j+1]
            group[j+1] = c 
            j += 1 
        i += 1
    return group


def find_community_by_decreasing_popularity(network):
    """
        Paramètres
    network : TYPE dictionnaire
    description: réseau d'amis

    Trier l'ensemble des personnes du réseau selon l'ordre décroissant de 
    popularité puis retourne la communauté trouvée en appliquant l'heuristique 
    de construction de communauté maximale expliquée plus haut
    """
    clefs = list(network)
    tab_tri = order_by_decreasing_popularity(network, clefs)
    commu_max = find_community(network, tab_tri)
    return commu_max


def find_community_from_person(network, person):
    """
    Paramètres
    network : TYPE dictionnaire
    description : réseau d'amis
    person : TYPE chaîne de caractère
    description : personne faisant partie du réseau

    Retourne une communauté maximale contenant cette personne selon l'heuristique décrite ci-dessus
    """
    tab = [person]
    group = order_by_decreasing_popularity(network, network[person])
    i = 0
    while i < len(group):
        if all_his_friends(network, group[i], tab):
            tab.append(group[i])
        i += 1
    return tab


def find_max_community(network):
    """
    Paramètres
    network : TYPE dictionnaire
    description : réseau d'amis

    Retourne la plus grande communauté trouvée
    """
    i = 0
    clefs = list(network)
    tab = []
    while i < len(clefs): #Boucle permettant de trouver la plus grande communauté d'un réseau
        if len(find_community_from_person(network,clefs[i])) > len(tab):
            tab = find_community_from_person(network, clefs[i])
        i += 1
    return tab