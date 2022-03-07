import {useState} from "react";
import axios from "axios";
import { useSelector, useDispatch } from 'react-redux'
import ReactModal from 'react-modal';
import {AdminNav} from "./AdminNav";

function fetchQuestionSets(token, setQuestionSets) {
    axios.get("http://www.uniquechange.com/fwApp/api/admin/questionsets_get.php", { headers: {"X-Auth-Token": token} })
        .then(response => {
            let data = response.data;
            setQuestionSets(data);
        })
        .catch(error => {
            console.log(error);
        });
}

function questionSetDeleteBackgroundAudio(token, questionSetId) {
    return axios.get("http://www.uniquechange.com/fwApp/api/admin/questionsets_backgroundaudio_del.php?id="+questionSetId, { headers: {"X-Auth-Token": token} })
        .catch(error => {
            console.log(error);
        });
}

function uuidv4() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

function sortOn(array, key){
    return array.sort(function(a, b){
        if(a[key] < b[key]){
            return -1;
        }else if(a[key] > b[key]){
            return 1;
        }
        return 0;
    });
}

function addBackgroundAudioToQuestionSet(token, qsid, audio, file) {
    let formData = new FormData();
    formData.append("fileToUpload", file);

    return axios({
        url: "http://www.uniquechange.com/fwApp/api/admin/questionsets_backgroundaudio_add.php?id="+qsid+"&audio="+audio,
        method: "POST",
        headers: {
            "X-Auth-Token": token,
            'Content-Type': 'multipart/form-data'
        },
        data: formData,
    })
        .then(response => {
            console.log("Add group");
        })
        .catch(error => {
            console.log(error);
        });
}

function questionDeleteAudio(token, questionId) {
    return axios.get("http://www.uniquechange.com/fwApp/api/admin/questions_audio_del.php?id="+questionId, { headers: {"X-Auth-Token": token} })
        .catch(error => {
            console.log(error);
        });
}

function questionDeleteAudioDetails(token, questionId) {
    return axios.get("http://www.uniquechange.com/fwApp/api/admin/questions_audiodetails_del.php?id="+questionId, { headers: {"X-Auth-Token": token} })
        .catch(error => {
            console.log(error);
        });
}

function addAudioToQuestion(token, qsid, audio, file) {
    let formData = new FormData();
    formData.append("fileToUpload", file);

    return axios({
        url: "http://www.uniquechange.com/fwApp/api/admin/questions_audio_add.php?id="+qsid+"&audio="+audio,
        method: "POST",
        headers: {
            "X-Auth-Token": token,
            'Content-Type': 'multipart/form-data'
        },
        data: formData,
    })
        .then(response => {
        })
        .catch(error => {
            console.log(error);
        });
}

function addAudioDetailsToQuestion(token, qsid, audio, file) {
    let formData = new FormData();
    formData.append("fileToUpload", file);

    return axios({
        url: "http://www.uniquechange.com/fwApp/api/admin/questions_audiodetails_add.php?id="+qsid+"&audio="+audio,
        method: "POST",
        headers: {
            "X-Auth-Token": token,
            'Content-Type': 'multipart/form-data'
        },
        data: formData,
    })
        .then(response => {
        })
        .catch(error => {
            console.log(error);
        });
}

function addImageToQuestion(token, qsid, image, file, altText) {
    let formData = new FormData();
    formData.append("fileToUpload", file);

    return axios({
        url: "http://www.uniquechange.com/fwApp/api/admin/questions_image_add.php?id="+qsid+"&image="+image+"&alt="+altText,
        method: "POST",
        headers: {
            "X-Auth-Token": token,
            'Content-Type': 'multipart/form-data'
        },
        data: formData,
    })
        .then(response => {
        })
        .catch(error => {
            console.log(error);
        });
}

export const AdminQuestionSets = () => {
    let [questionSets, setQuestionSets] = useState([]);
    let [activeQuestionSetId, setActiveQuestionSetId] = useState(null)
    let [activeQuestionId, setActiveQuestionId] = useState(null)
    let [backgroundAudioFile, setBackgroundAudioFile] = useState(null)
    let [questionAudioFile, setQuestionAudioFile] = useState(null)
    let [questionDetailsAudioFile, setQuestionDetailsAudioFile] = useState(null)
    let [questionImageFile, setQuestionImageFile] = useState(null)
    let [questionImageAltText, setQuestionImageAltText] = useState(null)
    let [filterUnphased, setFilterUnphased] = useState(false)

    const token = useSelector((state) => state.userDetails.token)

    if(questionSets.length === 0) {
        fetchQuestionSets(token, setQuestionSets);
    }

    let activeQuestionSet = questionSets.find((qs) => qs.ID === activeQuestionSetId);
    let activeQuestion = activeQuestionSet?.questions?.find((q) => q.ID === activeQuestionId);

    let filteredQuestionSets = questionSets.filter((qs) => {
        if(filterUnphased) {
            return qs.phase_title == null;
        } else {
            return true;
        }
    })

    return (
        <div>
            <AdminNav/>
            <div>
                <h4>Filters</h4>
                <label htmlFor="filterNoPhase">Not in a phase: </label>
                <input className="form-check-input" id="filterNoPhase" type="checkbox" onChange={(e) => setFilterUnphased(e.target.checked)}/>
            </div>
            <table className="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th>Title</th>
                    <th>Edit</th>
                    {/*<th>Delete</th>*/}
                </tr>
                </thead>
                <tbody>
                    { filteredQuestionSets.map((qs) =>
                        <tr>
                            <td>{qs.ID}</td>
                            <td>{qs.title}</td>
                            <td>
                                <button className="btn btn-primary" onClick={() => {
                                    setActiveQuestionSetId(qs.ID);
                                }}>Edit</button>
                            </td>
                            {/*<td>*/}
                            {/*    <button className="btn btn-danger" onClick={() => {*/}
                            {/*        // deleteGroup(token, g.group.id);*/}
                            {/*        // fetchGroups(token, setFacilitators);*/}
                            {/*    }}>Delete</button>*/}
                            {/*</td>*/}
                        </tr>
                    )}
                </tbody>
            </table>
            {/*<input type="text" onChange={(t) => setGroupName(t.target.value)}/>*/}
            {/*<button onClick={() => {*/}
            {/*    addGroup(token, groupName);*/}
            {/*    fetchGroups(token, setFacilitators);*/}
            {/*}}>Add Group</button>*/}

            { activeQuestionSetId != null && <ReactModal isOpen={true}>
                <div className="container">
                    <h3>Editing Question Set: '{activeQuestionSetId}'</h3>
                    <p>Specialism: '{questionSets.find((qs) => qs.ID === activeQuestionSetId).specialism}'</p>
                    <p>Type: '{questionSets.find((qs) => qs.ID === activeQuestionSetId).type}'</p>
                    <p>Frequency: '{questionSets.find((qs) => qs.ID === activeQuestionSetId).frequency}'</p>
                    <p>Acknowledgements: '{questionSets.find((qs) => qs.ID === activeQuestionSetId).acknowledgements}'</p>
                    <p>AcademicSupport: '{questionSets.find((qs) => qs.ID === activeQuestionSetId).academicSupport}'</p>
                    <p>Title: '{questionSets.find((qs) => qs.ID === activeQuestionSetId).title}'</p>
                    <p>Preparation: '{questionSets.find((qs) => qs.ID === activeQuestionSetId).preparation}'</p>
                    <p>Random: '{questionSets.find((qs) => qs.ID === activeQuestionSetId).random}'</p>
                    <p>Background: '{questionSets.find((qs) => qs.ID === activeQuestionSetId).background}'</p>
                    <p>Phase: '{questionSets.find((qs) => qs.ID === activeQuestionSetId).phase_title}'</p>

                    <h4>Background Audio</h4>
                    {activeQuestionSet.background_audio &&
                        <>
                            <audio controls>
                              <source src={"http://uniquechange.com/fwApp/audio-store/" + activeQuestionSet.background_audio + ".mp3"} type="audio/mpeg"/>
                            </audio>
                        </>
                    }
                    <div className="row col-4 mt-3">
                        <div className="input-group mb-3">
                            <input type="file" className="form-control" placeholder="Background Audio"
                                   aria-label="Background Audio" aria-describedby="basic-addon2" onChange={(t) => setBackgroundAudioFile(t.target.files[0])}/>
                            <div className="input-group-append">
                                <button className="btn btn-primary" onClick={() => {
                                    addBackgroundAudioToQuestionSet(token, activeQuestionSetId, activeQuestionSet.background_audio ?? uuidv4(), backgroundAudioFile).then((res) => {
                                        fetchQuestionSets(token, setQuestionSets);
                                    });
                                }}>Upload</button>
                            </div>
                        </div>

                        <div className="input-group mb-3">
                            <button className="btn btn-danger" onClick={() => {
                                questionSetDeleteBackgroundAudio(token, activeQuestionSetId).then((res) => {
                                    fetchQuestionSets(token, setQuestionSets);
                                });
                            }}>Delete</button>
                        </div>
                    </div>

                    <h4>Questions</h4>
                    <table className="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th>Question</th>
                            <th>Edit</th>
                        </tr>
                        </thead>
                        <tbody>
                        { sortOn(activeQuestionSet.questions, "qOrder").map((q) =>
                            <tr>
                                <td>{q.ID}</td>
                                <td>{q.question}</td>
                                <td>
                                    <button className="btn btn-primary" onClick={() => {
                                        setActiveQuestionId(q.ID);
                                    }}>Edit</button>
                                </td>
                            </tr>) }
                        </tbody>
                    </table>
                    <br/>
                    <button className="btn btn-danger" onClick={() => setActiveQuestionSetId(null)}>Close</button>
                </div>
            </ReactModal>
            }

            {activeQuestionId != null && <ReactModal isOpen={true}>
                <h3>Editing Question: '{activeQuestionId}'</h3>
                <p>Question: '{activeQuestion.question}'</p>
                <p>Order: '{activeQuestion.qOrder}'</p>
                <p>question Set: '{activeQuestion.questionSet}'</p>
                <p>Repeats: '{activeQuestion.repeats}'</p>
                <p>Scaffold: '{activeQuestion.scaffold}'</p>
                <p>Details: '{activeQuestion.details}'</p>

                <h4>Audio</h4>
                {activeQuestion.audio &&
                    <>
                        <audio controls>
                            <source src={"http://uniquechange.com/fwApp/audio-store/" + activeQuestion.audio + ".mp3"} type="audio/mpeg"/>
                        </audio>
                    </>
                }
                <div className="row col-4 mt-3">
                    <div className="input-group mb-3">
                        <input type="file" className="form-control" placeholder="Audio"
                               aria-label="Audio" aria-describedby="basic-addon2" onChange={(t) => setQuestionAudioFile(t.target.files[0])}/>
                        <div className="input-group-append">
                            <button className="btn btn-primary" onClick={() => {
                                addAudioToQuestion(token, activeQuestionId, activeQuestion.audio ?? uuidv4(), questionAudioFile).then((res) => {
                                    fetchQuestionSets(token, setQuestionSets);
                                });
                            }}>Upload</button>
                        </div>
                    </div>

                    <div className="input-group mb-3">
                        <button className="btn btn-danger" onClick={() => {
                            questionDeleteAudio(token, activeQuestionId).then((res) => {
                                fetchQuestionSets(token, setQuestionSets);
                            });
                        }}>Delete</button>
                    </div>
                </div>

                <h4>Audio Details</h4>
                {activeQuestion.audio_details &&
                    <>
                        <audio controls>
                            <source src={"http://uniquechange.com/fwApp/audio-store/" + activeQuestion.audio_details + ".mp3"} type="audio/mpeg"/>
                        </audio>
                    </>
                }
                <div className="row col-4 mt-3">
                    <div className="input-group mb-3">
                        <input type="file" className="form-control" placeholder="Audio Details"
                               aria-label="Audio Details" aria-describedby="basic-addon2" onChange={(t) => setQuestionDetailsAudioFile(t.target.files[0])}/>
                        <div className="input-group-append">
                            <button className="btn btn-primary" onClick={() => {
                                addAudioDetailsToQuestion(token, activeQuestionId, activeQuestion.audio_details ?? uuidv4(), questionDetailsAudioFile).then((res) => {
                                    fetchQuestionSets(token, setQuestionSets);
                                });
                            }}>Upload</button>
                        </div>
                    </div>

                    <div className="input-group mb-3">
                        <button className="btn btn-danger" onClick={() => {
                            questionDeleteAudioDetails(token, activeQuestionId).then((res) => {
                                fetchQuestionSets(token, setQuestionSets);
                            });
                        }}>Delete</button>
                    </div>
                </div>


                <h4>Image</h4>
                {activeQuestion.image &&
                    <img style={{width: "auto", height: 128}} src={"http://uniquechange.com/fwApp/image-store/" + activeQuestion.image + ".png"} alt={activeQuestion.image_alttext}/>
                }
                <div className="row col-4 mt-3">
                    <div className="input-group mb-3">
                        <input type="file" className="form-control" placeholder="Image"
                               aria-label="Image" aria-describedby="basic-addon2" onChange={(t) => setQuestionImageFile(t.target.files[0])}/>
                    </div>
                    <div className="input-group mb-3">
                        <input type="text" className="form-control" placeholder="Alt Text"
                               aria-label="Alt Text" aria-describedby="basic-addon2" onChange={(t) => setQuestionImageAltText(t.target.value)}/>
                    </div>
                    <div className="input-group-append">
                        <button className="btn btn-primary" onClick={() => {
                            addImageToQuestion(token, activeQuestionId, activeQuestion.image ?? uuidv4(), questionImageFile, questionImageAltText).then((res) => {
                                fetchQuestionSets(token, setQuestionSets);
                            });
                        }}>Upload</button>
                    </div>

                    {/*<div className="input-group mb-3">*/}
                    {/*    <button className="btn btn-danger" onClick={() => {*/}
                    {/*        questionDeleteAudioDetails(token, activeQuestionId).then((res) => {*/}
                    {/*            fetchQuestionSets(token, setQuestionSets);*/}
                    {/*        });*/}
                    {/*    }}>Delete</button>*/}
                    {/*</div>*/}
                </div>


                <br/>

                <button className="btn btn-danger" onClick={() => setActiveQuestionId(null)}>Close</button>

            </ReactModal>
            }
            </div>
    );
};
