from flask import Flask, jsonify, request, abort
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from nltk.stem import PorterStemmer
from collections import Counter
from num2words import num2words
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory

import nltk
import os
import string
import numpy as np
import copy
import pandas as pd
import pickle
import re
import math
app = Flask(__name__)

tasks = []  # Initialize the tasks list
def convert_lower_case(data):
    return np.char.lower(data)

def remove_stop_words(data):
    stop_words = stopwords.words('indonesian')
    words = word_tokenize(str(data))
    new_text = ""
    for w in words:
        if w not in stop_words and len(w) > 1:
            new_text = new_text + " " + w
    return new_text

def remove_punctuation(data):
    symbols = "!\"#$%&()*+-./:;<=>?@[\]^_`{|}~\n"
    for i in range(len(symbols)):
        data = np.char.replace(data, symbols[i], ' ')
        data = np.char.replace(data, "  ", " ")
    data = np.char.replace(data, ',', '')
    return data

def remove_apostrophe(data):
    return np.char.replace(data, "'", "")

def stemming(data):
    # stemmer= PorterStemmer()
    factory = StemmerFactory()
    stemmer = factory.create_stemmer()
    tokens = word_tokenize(str(data))
    new_text = ""
    for w in tokens:
        new_text = new_text + " " + stemmer.stem(w)
    return new_text

def convert_numbers(data):
    tokens = word_tokenize(str(data))
    new_text = ""
    for w in tokens:
        try:
            w = num2words(int(w))
        except:
            a = 0
        new_text = new_text + " " + w
    new_text = np.char.replace(new_text, "-", " ")
    return new_text

def remove_slashp(data):
    return np.char.replace(data, "</p>", "")

def remove_slashp2(data):
    return np.char.replace(data, "<p>", "")
def remove_slashp3(data):
    return np.char.replace(data, "/p", "")
def remove_slashp4(data):
    return np.char.replace(data, "<p", "")

def preprocess(data):
    data = convert_lower_case(data)
    data = remove_punctuation(data) #remove comma seperately
    data = remove_apostrophe(data)
    data = remove_slashp(data)
    data = remove_slashp2(data)
    data = remove_slashp3(data)
    data = remove_slashp4(data)
    data = remove_stop_words(data)
    data = convert_numbers(data)
    data = stemming(data)
    data = remove_punctuation(data)
    data = convert_numbers(data)
    data = stemming(data) #needed again as we need to stem the words
    data = remove_punctuation(data) #needed again as num2word is giving few hypens and commas fourty-one
    data = remove_stop_words(data) #needed again as num2word is giving stop words 101 - one hundred and one
    return data

###############################################

csv_file_path = 'XXXX_output.csv'

# Read the CSV file into a DataFrame
df = pd.read_csv(csv_file_path)

# Convert the DataFrame to a dictionary
loaded_dict = {(row['Index'], row['Subkey']): row['Value'] for _, row in df.iterrows()}

tf_idf = loaded_dict

############################################

def matching_score(k, query):
    preprocessed_query = preprocess(query)
    tokens = word_tokenize(str(preprocessed_query))

    #print("Matching Score")
    #print("\nQuery:", query)
    #print("")
    # print(tokens)

    query_weights = {}

    for key in tf_idf:
        if key[1] in tokens:
            try:
                query_weights[key[0]] += tf_idf[key]
            except:
                query_weights[key[0]] = tf_idf[key]

    query_weights = sorted(query_weights.items(), key=lambda x: x[1], reverse=True)

    # print("")

    l = []

    for i in query_weights[:10]:
        l.append(i[0])

    return l,tokens


@app.route('/hello/', methods=['GET', 'POST'])
def welcome():
    return jsonify({'name': 'Jimit', 'address': 'India'})

@app.route('/informationretrieval/', methods=['POST'])
def information_retrieval():
    print(request.json)
    print("test")
    if not request.json or not 'title' in request.json:
        abort(400)
    task = {
        'id': len(tasks) + 1,
        'title': request.json['title'],
        'description': request.json.get('description', ""),
        'done': False
    }
    tasks.append(task)
    return jsonify({'task': task}), 201

@app.route('/api/resource', methods=['POST'])
def create_resource():
    data = request.json

    if 'name' not in data:
        return jsonify({'error': 'Missing required fields'}), 400
    id_value, tokens_value = matching_score(10, data['name'])
    name = data['name']
    value = [ 77, 19, 18, 15, 7, 6, 5, 4, 3, 2, 1]

    # Perform any processing or database operations here

    response = {
        'name': name,
        'token' : tokens_value,
        'value': id_value
    }

    return jsonify(response), 201

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=105)